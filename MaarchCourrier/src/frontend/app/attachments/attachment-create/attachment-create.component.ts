import { Component, OnInit, Input, Inject, ViewChildren, QueryList } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { TranslateService } from '@ngx-translate/core';
import { catchError, tap, filter, distinctUntilChanged, take } from 'rxjs/operators';
import { of } from 'rxjs';
import { NotificationService } from '@service/notification/notification.service';
import { MatDialog, MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { AppService } from '@service/app.service';
import { DocumentViewerComponent } from '../../viewer/document-viewer.component';
import { SortPipe } from '../../../plugins/sorting.pipe';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { ConfirmComponent } from '../../../plugins/modal/confirm.component';
import { FunctionsService } from '@service/functions.service';
import { ContactService } from '@service/contact.service';
import { ContactAutocompleteComponent } from '../../contact/autocomplete/contact-autocomplete.component';

@Component({
    templateUrl: "attachment-create.component.html",
    styleUrls: [
        'attachment-create.component.scss',
        '../../indexation/indexing-form/indexing-form.component.scss'
    ],
    providers: [SortPipe, ContactService],
})

export class AttachmentCreateComponent implements OnInit {

    

    loading: boolean = true;

    sendMassMode: boolean = false;

    sendingData: boolean = false;

    attachmentsTypes: any[] = [];

    creationMode: boolean = true;

    attachFormGroup: FormGroup[] = [];

    attachments: any[] = [];

    now: Date = new Date();

    // To catch previous index to update file
    asyncIndexTab: number = 0;
    
    indexTab: number = 0;

    resourceSubject: string = '';
    resourceContacts: any[] = [];

    selectedContact = new FormControl();

    loadingContact: boolean = false;

    @Input('resId') resId: number = null;


    @ViewChildren('appDocumentViewer') appDocumentViewer: QueryList<DocumentViewerComponent>;
    @ViewChildren('contactAutocomplete') contactAutocomplete: ContactAutocompleteComponent;

    constructor(
        public translate: TranslateService,
        public http: HttpClient,
        @Inject(MAT_DIALOG_DATA) public data: any,
        public dialogRef: MatDialogRef<AttachmentCreateComponent>,
        public appService: AppService,
        private notify: NotificationService,
        private sortPipe: SortPipe,
        public dialog: MatDialog,
        public functions: FunctionsService,
        private contactService: ContactService, ) {
    }

    async ngOnInit(): Promise<void> {

        await this.loadAttachmentTypes();

        await this.loadResource();

        this.loading = false;
    }

    loadAttachmentTypes() {
        return new Promise((resolve, reject) => {
            this.http.get('../rest/attachmentsTypes').pipe(
                tap((data: any) => {
                    Object.keys(data.attachmentsTypes).forEach(templateType => {
                        if (data.attachmentsTypes[templateType].show) {
                            this.attachmentsTypes.push({
                                id: templateType,
                                ...data.attachmentsTypes[templateType]
                            });
                        }
                    });
                    this.attachmentsTypes = this.sortPipe.transform(this.attachmentsTypes, 'label');
                    resolve(true)
                }),
                catchError((err: any) => {
                    this.notify.handleSoftErrors(err);
                    this.dialogRef.close('');
                    return of(false);
                })
            ).subscribe();
        });
    }

    loadResource() {
        return new Promise((resolve, reject) => {
            this.http.get(`../rest/resources/${this.data.resIdMaster}?light=true`).pipe(
                tap(async (data: any) => {
                    let contact: any = '';
                    if (data.categoryId === 'outgoing') {
                        if (!this.functions.empty(data.recipients) && data.recipients.length > 0) {
                            data.recipients = data.recipients.filter((contact: any) => contact.type !== 'entity');
                            await this.getContacts(data.recipients);
                            contact = !this.functions.empty(data.recipients) ? [{ id: this.resourceContacts[0].id, type: this.resourceContacts[0].type }] : '';
                        }
                    } else {
                        if (!this.functions.empty(data.senders) && data.senders.length > 0) {
                            data.senders = data.senders.filter((contact: any) => contact.type !== 'entity');
                            await this.getContacts(data.senders);
                            contact = !this.functions.empty(data.senders) ? [{ id: this.resourceContacts[0].id, type: this.resourceContacts[0].type }] : '';
                        }
                    }

                    this.resourceSubject = data.subject;
                    this.attachments.push({
                        title: new FormControl({ value: data.subject, disabled: false }, [Validators.required]),
                        recipient: new FormControl({ value: contact, disabled: false }),
                        type: new FormControl({ value: 'response_project', disabled: false }, [Validators.required]),
                        validationDate: new FormControl({ value: '', disabled: false }),
                        format: new FormControl({ value: '', disabled: false }, [Validators.required]),
                        encodedFile: new FormControl({ value: '', disabled: false }, [Validators.required])
                    });
                    setTimeout(() => {
                        this.getAttachType('response_project', 0);
                    }, 800);

                    this.attachFormGroup.push(new FormGroup(this.attachments[0]));

                    if (data.categoryId === 'outgoing') {
                        if (!this.functions.empty(data.recipients) && data.recipients.length > 1) {
                            this.toggleSendMass();
                        }
                    } else {
                        if (!this.functions.empty(data.senders) && data.senders.length > 1) {
                            this.toggleSendMass();
                        }
                    }

                    resolve(true);
                }),
                catchError((err: any) => {
                    this.notify.handleSoftErrors(err);
                    this.dialogRef.close('');
                    return of(false);
                })
            ).subscribe();
        });
    }

    async getContacts(contacts: any) {
        this.resourceContacts = [];
        await Promise.all(contacts.map(async (elem: any) => {
            await this.getContact(elem.id, elem.type);
        }));

        this.resourceContacts = this.sortPipe.transform(this.resourceContacts, 'label');
    }

    selectContact(contact: any) {

        this.loadingContact = true;
        const contactChosen = JSON.parse(JSON.stringify(this.resourceContacts.filter(resContact => resContact.id === contact.id && resContact.type === contact.type)[0]));

        this.attachments[this.indexTab].recipient.setValue([contactChosen]);

        setTimeout(() => {
            this.loadingContact = false;
        }, 0);

        this.selectedContact.reset();
    }

    getContact(contactId: number, type: string) {
        return new Promise((resolve, reject) => {
            if (type === 'contact') {
                this.http.get('../rest/contacts/' + contactId).pipe(
                    tap((data: any) => {
                        this.resourceContacts.push({
                            id: data.id,
                            type: 'contact',
                            label: this.contactService.formatContact(data)
                        });
                        resolve(true);
                    }),
                    catchError((err: any) => {
                        this.notify.handleSoftErrors(err);
                        resolve(false);
                        return of(false);
                    })
                ).subscribe();
            } else if (type === 'user') {
                this.http.get('../rest/users/' + contactId).pipe(
                    tap((data: any) => {
                        this.resourceContacts.push({
                            id: data.id,
                            type: 'user',
                            label: `${data.firstname} ${data.lastname}`
                        });
                        resolve(true);
                    }),
                    catchError((err: any) => {
                        this.notify.handleSoftErrors(err);
                        resolve(false);
                        return of(false);
                    })
                ).subscribe();
            } else if (type === 'entity') {
                this.http.get('../rest/entities/' + contactId).pipe(
                    tap((data: any) => {
                        this.resourceContacts.push({
                            id: data.id,
                            type: 'entity',
                            label: data.entity_label
                        });
                        resolve(true);
                    }),
                    catchError((err: any) => {
                        this.notify.handleSoftErrors(err);
                        resolve(false);
                        return of(false);
                    })
                ).subscribe();
            }
        });
    }

    selectAttachType(attachment: any, type: any) {
        attachment.type = type.id;
    }

    formatAttachments() {
        let formattedAttachments: any[] = [];
        this.attachments.forEach((element, index: number) => {
            formattedAttachments.push({
                resIdMaster: this.data.resIdMaster,
                type: element.type.value,
                title: element.title.value,
                recipientId: element.recipient.value.length > 0 ? element.recipient.value[0].id : null,
                recipientType: element.recipient.value.length > 0 ? element.recipient.value[0].type : null,
                validationDate: element.validationDate.value !== '' ? element.validationDate.value : null,
                encodedFile: element.encodedFile.value,
                format: element.format.value
            });
        });

        return formattedAttachments;
    }

    onSubmit(mode: string = 'default') {
        this.appDocumentViewer.toArray()[this.indexTab].getFile().pipe(
            distinctUntilChanged(),
            tap((data) => {
                this.attachments[this.indexTab].encodedFile.setValue(data.content);
                this.attachments[this.indexTab].format.setValue(data.format);
            }),
            tap(async () => {
                if (this.isValid()) {
                    let resId: any = null;
                    this.sendingData = true;
                    const attach = this.formatAttachments();

                    for (const attachment of attach) {
                        resId = await this.saveAttachment(attachment);
                    }

                    if (this.sendMassMode && resId !== null && mode === 'mailing') {
                        await this.generateMailling(resId);
                    }

                    this.sendingData = false;
                    this.notify.success(this.translate.instant('lang.attachmentAdded'));
                    this.dialogRef.close('success');

                } else {
                    this.sendingData = false;
                    this.notify.error(this.translate.instant('lang.mustCompleteAllAttachments'));
                }
            })
        ).subscribe();
    }

    saveAttachment(attachment: any) {
        attachment.status = this.sendMassMode ? 'SEND_MASS' : 'A_TRA';

        return new Promise((resolve, reject) => {
            this.http.post(`../rest/attachments`, attachment).pipe(
                tap((data: any) => {
                    resolve(data.id);
                }),
                catchError((err: any) => {
                    this.notify.handleSoftErrors(err);
                    this.dialogRef.close('');
                    return of(false);
                })
            ).subscribe();
        });
    }

    generateMailling(resId: number) {
        return new Promise((resolve, reject) => {
            this.http.post(`../rest/attachments/${resId}/mailing`, {}).pipe(
                tap(() => {
                    resolve(true);
                }),
                catchError((err: any) => {
                    this.notify.handleSoftErrors(err);
                    this.dialogRef.close('');
                    return of(false);
                })
            ).subscribe();
        });
    }

    isValid() {
        let state = true;
        this.attachFormGroup.forEach(formgroup => {
            Object.keys(formgroup.controls).forEach(key => {
                formgroup.controls[key].markAsTouched();
            });
            if (formgroup.status === 'INVALID') {
                state = false;
            }

        });
        return state;
    }

    isPjValid(index: number) {
        let state = true;
        if (this.attachFormGroup[index].status === 'INVALID') {
            state = false;
        }
        return state;
    }

    isDocLoading() {
        let state = false;
        this.appDocumentViewer.toArray().forEach((app, index: number) => {
            if (app.isEditingTemplate()) {
                state = true;
            }
        });
        return state;
    }

    canValidatePj() {
        let state = false;
        this.appDocumentViewer.toArray().forEach((app, index: number) => {
            if (app.isEditingTemplate() && app.editor.async) {
                state = true;
            }
        });
        return state;
    }

    setDatasViewer(i: number) {
        let datas: any = {};
        Object.keys(this.attachments[i]).forEach(element => {
            if (['title', 'validationDate', 'recipient'].indexOf(element) > -1) {
                if (element === 'recipient' && this.attachments[i][element].value.length > 0) {
                    datas['recipientId'] = this.attachments[i][element].value[0].id
                    datas['recipientType'] = this.attachments[i][element].value[0].type
                } else {
                    datas['attachment_' + element] = this.attachments[i][element].value;
                }
            }
        });
        datas['resId'] = this.data.resIdMaster;
        if (this.sendMassMode) {
            datas['inMailing'] = true;
        }
        this.appDocumentViewer.toArray()[i].setDatas(datas);
    }

    newPj() {
        this.attachments.push({
            title: new FormControl({ value: this.resourceSubject, disabled: false }, [Validators.required]),
            recipient: new FormControl({ value: !this.functions.empty(this.resourceContacts[this.attachments.length]) ? [{ id: this.resourceContacts[this.attachments.length].id, type: this.resourceContacts[this.attachments.length].type }] : null, disabled: false }),
            type: new FormControl({ value: 'response_project', disabled: false }, [Validators.required]),
            validationDate: new FormControl({ value: null, disabled: false }),
            encodedFile: new FormControl({ value: '', disabled: false }, [Validators.required]),
            format: new FormControl({ value: '', disabled: false }, [Validators.required])
        });
        this.attachFormGroup.push(new FormGroup(this.attachments[this.attachments.length - 1]));
        this.indexTab = this.attachments.length - 1;
        setTimeout(() => {
            this.getAttachType('response_project', this.indexTab);
        }, 800);
    }

    updateFile(index: number) {
        if (this.functions.empty(this.attachments[this.asyncIndexTab].encodedFile.value)) {            
            this.appDocumentViewer.toArray()[this.asyncIndexTab].getFile().pipe(
                take(1),
                tap((data) => {
                    this.attachments[this.asyncIndexTab].encodedFile.setValue(data.content);
                    this.attachments[this.asyncIndexTab].format.setValue(data.format);
                    this.asyncIndexTab = index;
                }),
            ).subscribe();
        } else {
            this.asyncIndexTab = index;
        }



    }

    removePj(i: number) {
        const dialogRef = this.dialog.open(ConfirmComponent, { panelClass: 'maarch-modal', autoFocus: false, disableClose: true, data: { title: this.translate.instant('lang.delete') + ' : ' + this.translate.instant('lang.attachmentShort') + ' n°' + (i + 1), msg: this.translate.instant('lang.confirmAction') } });

        dialogRef.afterClosed().pipe(
            filter((data: string) => data === 'ok'),
            tap(() => {
                this.indexTab = 0;
                this.asyncIndexTab = this.indexTab;
                this.attachments.splice(i, 1);
                this.attachFormGroup.splice(i, 1);
            }),
            catchError((err: any) => {
                this.notify.handleErrors(err);
                return of(false);
            })
        ).subscribe();

    }

    getAttachType(attachType: any, i: number) {
        this.appDocumentViewer.toArray()[i].loadTemplatesByResId(this.data.resIdMaster, attachType);
    }

    isEmptyField(field: any) {

        if (field.value === null) {
            return true;

        } else if (Array.isArray(field.value)) {
            if (field.value.length > 0) {
                return false;
            } else {
                return true;
            }
        } else if (String(field.value) !== '') {
            return false;
        } else {
            return true;
        }
    }

    toggleSendMass() {
        if (this.sendMassMode) {
            this.sendMassMode = !this.sendMassMode;
            this.selectedContact.enable();
        } else {
            if (this.attachments.length === 1) {
                this.sendMassMode = !this.sendMassMode;
                this.selectedContact.disable();
            } else {
                this.notify.error('Veuillez supprimer les <b>autres onglets PJ</b> avant de passer en <b>publipostage</b>.');
            }

        }
    }
}
