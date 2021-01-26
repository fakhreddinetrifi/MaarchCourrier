import { COMMA, SEMICOLON, FF_SEMICOLON } from '@angular/cdk/keycodes';
import { Component, OnInit, Inject, ViewChild, ElementRef } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { TranslateService } from '@ngx-translate/core';
import { NotificationService } from '@service/notification/notification.service';
import { MatChipInputEvent } from '@angular/material/chips';
import { MatDialog, MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { switchMap, map, catchError, filter, exhaustMap, tap, debounceTime, distinctUntilChanged, finalize } from 'rxjs/operators';
import { FormControl } from '@angular/forms';
import { FunctionsService } from '@service/functions.service';
import { CdkDragDrop, transferArrayItem } from '@angular/cdk/drag-drop';
import { ContactService } from '@service/contact.service';
import { AppService } from '@service/app.service';
import { ConfirmComponent } from '../../../plugins/modal/confirm.component';
import { PrivilegeService } from '@service/privileges.service';
import { HeaderService } from '@service/header.service';
import { Observable, of } from 'rxjs';
import { SummarySheetComponent } from '../../list/summarySheet/summary-sheet.component';

declare var $: any;
declare var tinymce: any;

@Component({
    selector: 'app-sent-resource-page',
    templateUrl: 'sent-resource-page.component.html',
    styleUrls: ['sent-resource-page.component.scss'],
    providers: [ContactService, AppService],
})
export class SentResourcePageComponent implements OnInit {

    loading: boolean = true;

    readonly separatorKeysCodes: number[] = [COMMA, SEMICOLON, FF_SEMICOLON, 190];

    availableEmailModels: any[] = [];
    availableSignEmailModels: any[] = [];

    resourceData: any = null;
    availableSenders: any[] = [];
    currentSender: any = {};

    recipients: any[] = [];

    copies: any[] = [];

    invisibleCopies: any[] = [];

    recipientsInput: FormControl = new FormControl();

    emailSignListForm = new FormControl();
    templateEmailListForm = new FormControl();

    filteredEmails: Observable<string[]>;

    showCopies: boolean = false;
    showInvisibleCopies: boolean = false;

    emailCreatorId: number = null;
    emailId: number = null;
    emailsubject: string = '';
    emailStatus: string = 'WAITING';
    emailContent: string = '';
    currentEmailAttachTool: string = '';
    emailAttachTool: any = {
        document: {
            icon: 'fa fa-file',
            title: this.translate.instant('lang.attachMainDocument'),
            list: []
        },
        notes: {
            icon: 'fas fa-pen-square',
            title: this.translate.instant('lang.attachNote'),
            list: []
        },
        attachments: {
            icon: 'fa fa-paperclip',
            title: this.translate.instant('lang.attachAttachment'),
            list: []
        },
        summarySheet: {
            icon: 'fas fa-scroll',
            title: this.translate.instant('lang.attachSummarySheet'),
            list: []
        },
    };
    emailAttach: any = {};

    encodedSummarySheet: any = null;
    summarySheetUnits: any = [];

    canManage: boolean = false;
    pdfMode: boolean = false;
    htmlMode: boolean = true;

    @ViewChild('recipientsField', { static: true }) recipientsField: ElementRef<HTMLInputElement>;
    @ViewChild('copiesField', { static: false }) copiesField: ElementRef<HTMLInputElement>;
    @ViewChild('invisibleCopiesField', { static: false }) invisibleCopiesField: ElementRef<HTMLInputElement>;

    constructor(
        public http: HttpClient,
        private notify: NotificationService,
        @Inject(MAT_DIALOG_DATA) public data: any,
        public dialog: MatDialog,
        public dialogRef: MatDialogRef<SentResourcePageComponent>,
        public functions: FunctionsService,
        private contactService: ContactService,
        public privilegeService: PrivilegeService,
        public headerService: HeaderService,
        public translate: TranslateService
    ) { }

    async ngOnInit(): Promise<void> {
        Object.keys(this.emailAttachTool).forEach(element => {
            if (element === 'document') {
                this.emailAttach[element] = {
                    id: this.data.resId,
                    isLinked: false,
                    original: false
                };
            } else {
                this.emailAttach[element] = [];
            }
        });

        await this.getAttachElements();

        if (this.data.emailId && this.data.emailType === 'email') {
            await this.getEmailData(this.data.emailId);
        } else if (this.data.emailId && this.data.emailType === 'acknowledgementReceipt') {
            await this.getAcknowledgementReceiptData(this.data.emailId);
        }

        if (this.canManageMail()) {
            this.initEmailModelsList();
            this.initEmailsList();
            this.initSignEmailModelsList();

            await this.getResourceData();
            await this.getUserEmails();

            if (this.emailStatus !== 'DRAFT') {
                this.setDefaultInfo();
            }
        }
        // this.loading = false;
        setTimeout(() => {
            this.initMce();
        }, 0);
    }

    initMce() {
        tinymce.init({
            selector: 'textarea#emailSignature',
            base_url: '../node_modules/tinymce/',
            setup: (editor: any) => {
                editor.on('init', (e: any) => {
                    this.loading = false;
                });
            },
            readonly: this.emailStatus === 'SENT',
            height: '400',
            suffix: '.min',
            language: this.translate.instant('lang.langISO').replace('-', '_'),
            language_url: `../node_modules/tinymce-i18n/langs/${this.translate.instant('lang.langISO').replace('-', '_')}.js`,
            menubar: false,
            statusbar: false,
            plugins: [
                'autolink'
            ],
            external_plugins: {
                'maarch_b64image': '../../src/frontend/plugins/tinymce/maarch_b64image/plugin.min.js'
            },
            toolbar_sticky: true,
            toolbar_drawer: 'floating',
            toolbar: this.emailStatus !== 'SENT' ?
                'undo redo | fontselect fontsizeselect | bold italic underline strikethrough forecolor | maarch_b64image | \
            alignleft aligncenter alignright alignjustify \
            bullist numlist outdent indent | removeformat' : false
        });
    }

    add(event: MatChipInputEvent, type: string): void {

        const input = event.input;
        const value = event.value;

        if ((value || '').trim()) {
            this[type].push(
                {
                    label: value.trim(),
                    email: value.trim(),
                    badFormat: this.isBadEmailFormat(value.trim())
                });
        }

        if (input) {
            input.value = '';
        }
    }

    isBadEmailFormat(email: string) {
        const regex = /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/g;

        return email.trim().match(regex) === null;
    }

    closeModal(state: string = '') {
        tinymce.remove();
        this.dialogRef.close(state);
    }

    addEmail(item: any, type: string) {
        this[type].splice(this[type].length - 1, 1);

        if (item.type === 'contactGroup') {
            this.http.get(`../rest/contactsGroups/${item.id}`).pipe(
                map((data: any) => {
                    data = data.contactsGroup.contacts.filter((contact: any) => !this.functions.empty(contact.email)).map((contact: any) => {
                        return {
                            label: contact.contact,
                            email: contact.email
                        };
                    });
                    return data;
                }),
                tap((data: any) => {
                    this[type] = this[type].concat(data);
                }),
                catchError((err) => {
                    this.notify.handleSoftErrors(err);
                    return of(false);
                })
            ).subscribe();
        } else {
            this[type].push({
                label: item.label,
                email: item.email
            });
        }
    }

    mergeEmailTemplate(templateId: any) {

        this.templateEmailListForm.reset();

        this.http.post(`../rest/templates/${templateId}/mergeEmail`, { data: { resId: this.data.resId } }).pipe(
            tap((data: any) => {

                const div = document.createElement('div');

                div.innerHTML = tinymce.get('emailSignature').getContent();

                if (div.getElementsByClassName('signature').length > 0) {

                    const signatureContent = div.getElementsByClassName('signature')[0].innerHTML;

                    div.getElementsByClassName('signature')[0].remove();

                    tinymce.get('emailSignature').setContent(`${div.innerHTML}${data.mergedDocument}<div class="signature">${signatureContent}</div>`);

                } else {
                    tinymce.get('emailSignature').setContent(`${tinymce.get('emailSignature').getContent()}${data.mergedDocument}`);
                }
                if (!this.htmlMode) {
                    tinymce.get('emailSignature').setContent(tinymce.get('emailSignature').getContent({ format: 'text' }));
                }

                if (!this.functions.empty(data.mergedSubject)) {
                    this.emailsubject = data.mergedSubject;
                }
            }),
            catchError((err) => {
                this.notify.handleSoftErrors(err);
                return of(false);
            })
        ).subscribe();
    }

    mergeSignEmailTemplate(templateId: any) {

        this.emailSignListForm.reset();

        this.http.get(`../rest/currentUser/emailSignatures/${templateId}`).pipe(
            tap((data: any) => {
                const div = document.createElement('div');

                div.innerHTML = tinymce.get('emailSignature').getContent();

                if (div.getElementsByClassName('signature').length > 0) {

                    div.getElementsByClassName('signature')[0].remove();

                    tinymce.get('emailSignature').setContent(`${div.innerHTML}<div class="signature">${data.emailSignature.content}</div>`);
                } else {
                    tinymce.get('emailSignature').setContent(`${tinymce.get('emailSignature').getContent()}<div class="signature">${data.emailSignature.content}</div>`);
                }
                if (!this.htmlMode) {
                    tinymce.get('emailSignature').setContent(tinymce.get('emailSignature').getContent({ format: 'text' }));
                }
            }),
            catchError((err) => {
                this.notify.handleSoftErrors(err);
                return of(false);
            })
        ).subscribe();
    }

    remove(item: any, type: string): void {
        if (this.canManageMail()) {
            const index = this[type].indexOf(item);

            if (index >= 0) {
                this[type].splice(index, 1);
            }
        }
    }

    getEmailData(emailId: number) {
        return new Promise((resolve) => {
            this.http.get(`../rest/emails/${emailId}`).pipe(
                tap((data: any) => {
                    this.emailCreatorId = data.userId;

                    this.recipients = data.recipients.map((item: any) => {
                        return {
                            label: item,
                            email: item,
                            badFormat: this.isBadEmailFormat(item)
                        };
                    });
                    this.copies = data.cc.map((item: any) => {
                        return {
                            label: item,
                            email: item,
                            badFormat: this.isBadEmailFormat(item)
                        };
                    });
                    this.invisibleCopies = data.cci.map((item: any) => {
                        return {
                            label: item,
                            email: item,
                            badFormat: this.isBadEmailFormat(item)
                        };
                    });

                    this.showCopies = this.copies.length > 0;
                    this.showInvisibleCopies = this.invisibleCopies.length > 0;


                    this.emailsubject = data.object;
                    this.emailStatus = data.status;

                    this.currentSender = {
                        entityId: data.sender.entityId,
                        label: data.sender.label,
                        email: data.sender.email
                    };

                    this.emailContent = data.body;
                    Object.keys(data.document).forEach(element => {
                        if (['id', 'isLinked', 'original'].indexOf(element) === -1) {
                            this.emailAttach[element] = [];
                            data.document[element].forEach((dataAttach: any) => {
                                const elem = this.emailAttachTool[element].list.filter((item: any) => item.id === dataAttach.id || item.id === dataAttach);
                                if (elem.length > 0) {
                                    this.emailAttach[element] = this.emailAttach[element].concat(elem.map((item: any) => {
                                        return {
                                            ...item,
                                            format: dataAttach.original || dataAttach.original === undefined ? item.format : 'pdf',
                                            original: dataAttach.original,
                                            size: dataAttach.original || dataAttach.original === undefined ? item.size : item.convertedDocument.size
                                        };
                                    }));
                                }
                            });
                        } else if (element === 'isLinked' && data.document.isLinked === true) {
                            this.emailAttach.document.isLinked = true;
                            this.emailAttach.document.format = data.document.original || data.document.original === undefined ? this.emailAttachTool.document.list[0].format : 'pdf';
                            this.emailAttach.document.original = data.document.original;
                            this.emailAttach.document.size = this.emailAttach.document.original ? this.emailAttachTool.document.list[0].size : this.emailAttachTool.document.list[0].convertedDocument.size;
                        }
                    });

                    resolve(true);
                }),
                catchError((err) => {
                    this.notify.handleSoftErrors(err);
                    resolve(false);
                    return of(false);
                })
            ).subscribe();
        });
    }

    getAcknowledgementReceiptData(emailId: number) {
        return new Promise((resolve) => {
            this.http.get(`../rest/acknowledgementReceipts/${emailId}`).pipe(
                tap((data: any) => {
                    this.currentSender = {
                        label: data.acknowledgementReceipt.userLabel,
                        email: data.acknowledgementReceipt.userLabel
                    };
                    this.recipients = [{
                        label: !this.functions.empty(data.acknowledgementReceipt.contact) ? this.contactService.formatContact(data.acknowledgementReceipt.contact) : this.translate.instant('lang.contactDeleted'),
                        email: !this.functions.empty(data.acknowledgementReceipt.contact.email) ? data.acknowledgementReceipt.contact.email : this.translate.instant('lang.withoutEmail')
                    }];

                    this.emailStatus = 'SENT';
                }),
                exhaustMap(() => this.http.get(`../rest/acknowledgementReceipts/${emailId}/content`)),
                tap((data: any) => {
                    this.pdfMode = data.format === 'pdf';

                    if (this.pdfMode) {
                        this.emailsubject = this.translate.instant('lang.ARPaper');
                        this.emailContent = data.encodedDocument;
                    } else {
                        this.emailsubject = this.translate.instant('lang.ARelectronic');
                        this.emailContent = this.b64DecodeUnicode(data.encodedDocument);
                    }
                    resolve(true);
                }),
                catchError((err) => {
                    this.notify.handleSoftErrors(err);
                    resolve(false);
                    return of(false);
                })
            ).subscribe();
        });
    }

    getResourceData() {
        return new Promise((resolve) => {
            this.http.get(`../rest/resources/${this.data.resId}?light=true`).pipe(
                tap((data: any) => {
                    this.resourceData = data;

                    this.emailAttach.document.chrono = this.resourceData.chrono;
                    this.emailAttach.document.label = this.resourceData.subject;

                    resolve(true);
                }),
                catchError((err) => {
                    this.notify.handleSoftErrors(err);
                    resolve(false);
                    return of(false);
                })
            ).subscribe();
        });
    }

    setDefaultInfo() {
        this.emailsubject = `[${this.resourceData.chrono}] ${this.resourceData.subject}`;
        this.emailsubject = this.emailsubject.substring(0, 70);
        if (this.headerService.user.entities.length === 0) {
            this.currentSender = this.availableSenders[0];
        } else {
            this.currentSender = this.availableSenders.filter(sender => sender.entityId === this.headerService.user.entities[0].id).length > 0 ? this.availableSenders.filter(sender => sender.entityId === this.headerService.user.entities[0].id)[0] : this.availableSenders[0];
        }
            if (!this.functions.empty(this.resourceData.senders)) {
                this.resourceData.senders.forEach((sender: any) => {
                    this.setSender(sender);
                });
            }
    }

    setSender(sender: any) {
        switch (sender.type) {
            case 'contact':
                this.http.get(`../rest/contacts/${sender.id}`).pipe(
                    tap((data: any) => {
                        if (!this.functions.empty(data.email)) {
                            this.recipients.push(
                                {
                                    label: this.contactService.formatContact(data),
                                    email: data.email
                                }
                            );
                        }
                    }),
                    catchError((err) => {
                        this.notify.handleSoftErrors(err);
                        return of(false);
                    })
                ).subscribe();
                break;

            case 'user':
                this.http.get(`../rest/users/${sender.id}`).pipe(
                    tap((data: any) => {
                        if (!this.functions.empty(data.mail)) {
                            this.recipients.push(
                                {
                                    label: this.contactService.formatContact(data),
                                    email: data.mail
                                }
                            );
                        }
                    }),
                    catchError((err) => {
                        this.notify.handleSoftErrors(err);
                        return of(false);
                    })
                ).subscribe();
                break;

            default:
                break;
        }
    }

    getUserEmails() {
        return new Promise((resolve) => {
            this.http.get('../rest/currentUser/availableEmails').pipe(
                tap((data: any) => {
                    this.availableSenders = data.emails;
                    resolve(true);
                }),
                catchError((err) => {
                    this.notify.handleSoftErrors(err);
                    resolve(false);
                    return of(false);
                })
            ).subscribe();
        });
    }

    getAttachElements() {
        return new Promise((resolve) => {
            this.http.get(`../rest/resources/${this.data.resId}/emailsInitialization`).pipe(
                tap((data: any) => {
                    Object.keys(data).forEach(element => {
                        if (element === 'resource') {
                            this.emailAttachTool.document.list = [];
                            if (!this.functions.empty(data[element])) {
                                this.emailAttachTool.document.list = [data[element]];
                            }
                        } else {
                            this.emailAttachTool[element].list = data[element].map((item: any) => {
                                if (item.attachInMail) {
                                    this.toggleAttachMail(item, element, item.status === 'SIGN' ? 'pdf' : 'original');
                                }
                                return {
                                    ...item,
                                    original: item.original !== undefined ? item.original : true,
                                    title: item.chrono !== undefined ? `${item.chrono} - ${item.label} (${item.typeLabel})` : `${item.label} (${item.typeLabel})`
                                };
                            });
                        }
                    });

                    resolve(true);
                }),
                catchError((err: any) => {
                    this.notify.handleSoftErrors(err);
                    resolve(false);
                    return of(false);
                })
            ).subscribe();
        });
    }

    initEmailsList() {
        this.recipientsInput.valueChanges.pipe(
            filter(value => value !== null),
            debounceTime(300),
            tap((value) => {
                if (value.length === 0) {
                    this.filteredEmails = of([]);
                }
            }),
            filter(value => value.length > 2),
            distinctUntilChanged(),
            switchMap(data => this.http.get('../rest/autocomplete/correspondents', { params: { 'search': data, 'searchEmails': 'true' } })),
            tap((data: any) => {
                data = data.filter((contact: any) => !this.functions.empty(contact.email) || contact.type === 'contactGroup').map((contact: any) => {
                    let label = '';
                    if (contact.type === 'user' || contact.type === 'contact') {
                        if (!this.functions.empty(contact.firstname) || !this.functions.empty(contact.lastname)) {
                            label = contact.firstname + ' ' + contact.lastname;
                        } else {
                            label = contact.company;
                        }
                    } else if (contact.type === 'contactGroup') {
                        label = `${contact.firstname} ${contact.lastname}`;
                    } else {
                        label = `${contact.lastname}`;
                    }
                    return {
                        id: contact.id,
                        type: contact.type,
                        label: label,
                        email: contact.email
                    };
                });
                this.filteredEmails = of(data);
            }),
            catchError((err) => {
                this.notify.handleSoftErrors(err);
                return of(false);
            })
        ).subscribe();
    }


    initEmailModelsList() {
        this.http.get(`../rest/resources/${this.data.resId}/emailTemplates`).pipe(
            tap((data: any) => {
                this.availableEmailModels = data.templates;
            }),
            catchError((err) => {
                this.notify.handleSoftErrors(err);
                return of(false);
            })
        ).subscribe();
    }

    initSignEmailModelsList() {
        this.http.get(`../rest/currentUser/emailSignatures`).pipe(
            tap((data: any) => {
                this.availableSignEmailModels = data.emailSignatures;
            }),
            catchError((err) => {
                this.notify.handleSoftErrors(err);
                return of(false);
            })
        ).subscribe();
    }

    resetAutocomplete() {
        this.filteredEmails = of([]);
    }

    onSubmit() {
        this.emailStatus = 'WAITING';

        if (this.data.emailId === null) {
            if (!this.isAllEmailRightFormat()) {
                this.notify.error(this.translate.instant('lang.badEmailsFormat'));
            } else {
                if (this.emailsubject === '') {
                    const dialogRef = this.dialog.open(ConfirmComponent, { panelClass: 'maarch-modal', autoFocus: false, disableClose: true, data: { title: this.translate.instant('lang.confirm'), msg: this.translate.instant('lang.warnEmptySubject') } });

                    dialogRef.afterClosed().pipe(
                        filter((data: string) => data === 'ok'),
                        tap(() => {
                            this.createEmail(true);
                        })
                    ).subscribe();
                } else {
                    this.createEmail(true);
                }
            }
        } else {
            this.updateEmail(true);
        }
    }

    async createEmail(closeModal: boolean = true) {
        if (this.summarySheetUnits.length !== 0) {
            await this.createSummarySheet();
        }

        this.http.post(`../rest/emails`, this.formatEmail()).pipe(
            tap(() => {
                if (this.emailStatus === 'DRAFT') {
                    // this.notify.success(this.translate.instant('lang.draftSaved'));
                } else {
                    this.notify.success(`${this.translate.instant('lang.sendingEmail')}...`);
                }

                if (closeModal) {
                    this.closeModal('success');
                }
            }),
            finalize(() => {
                if (this.emailStatus === 'DRAFT') {
                    this.closeModal('success');
                }
            }),
            catchError((err) => {
                this.notify.handleSoftErrors(err);
                return of(false);
            })
        ).subscribe();
    }

    deleteEmail() {
        const dialogRef = this.dialog.open(ConfirmComponent, { panelClass: 'maarch-modal', autoFocus: false, disableClose: true, data: { title: this.translate.instant('lang.delete'), msg: this.translate.instant('lang.confirmAction') } });

        dialogRef.afterClosed().pipe(
            filter((data: string) => data === 'ok'),
            exhaustMap(() => this.http.delete(`../rest/emails/${this.data.emailId}`)),
            tap(() => {
                this.notify.success(this.translate.instant('lang.emailDeleted'));
                this.closeModal('success');
            }),
            catchError((err) => {
                this.notify.handleSoftErrors(err);
                return of(false);
            })
        ).subscribe();
    }

    async updateEmail(closeModal: boolean = true) {
        if (this.summarySheetUnits.length !== 0) {
            await this.createSummarySheet();
        }

        this.http.put(`../rest/emails/${this.data.emailId}`, this.formatEmail()).pipe(
            tap(() => {
                if (this.emailStatus === 'DRAFT') {
                    // this.notify.success(this.translate.instant('lang.draftUpdated'));
                } else {
                    this.notify.success(`${this.translate.instant('lang.sendingEmail')}...`);
                }

                if (closeModal) {
                    this.closeModal('success');
                }
            }),
            finalize(() => {
                if (this.emailStatus === 'DRAFT') {
                    this.closeModal('success');
                }
            }),
            catchError((err) => {
                this.notify.handleSoftErrors(err);
                return of(false);
            })
        ).subscribe();
    }

    saveDraft() {
        if (this.canManageMail()) {
            this.emailStatus = 'DRAFT';
            if (this.data.emailId === null) {
                if (!this.functions.empty(tinymce.get('emailSignature').getContent())) {
                    this.createEmail(true);
                } else {
                    this.closeModal();
                }
            } else {
                this.updateEmail(true);
            }
        } else {
            this.closeModal();
        }
    }

    drop(event: CdkDragDrop<string[]>) {

        if (event.previousContainer !== event.container) {
            transferArrayItem(event.previousContainer.data,
                event.container.data,
                event.previousIndex,
                event.currentIndex);
        }
    }

    toggleAttachMail(item: any, type: string, mode: 'original' | 'pdf') {
        if (type === 'document') {
            if (this.emailAttach.document.isLinked === false) {
                this.emailAttach.document.isLinked = true;
                this.emailAttach.document.format = mode !== 'pdf' ? item.format : 'pdf';
                this.emailAttach.document.original = mode !== 'pdf';
                this.emailAttach.document.size = mode === 'pdf' ? item.convertedDocument.size : item.size;
            }
        } else {
            if (this.emailAttach[type].filter((attach: any) => attach.id === item.id).length === 0) {
                this.emailAttach[type].push({
                    ...item,
                    format: mode !== 'pdf' ? item.format : 'pdf',
                    original: mode !== 'pdf',
                    size: mode === 'pdf' ? item.convertedDocument.size : item.size
                });
            }
        }
    }

    removeAttachMail(index: number, type: string) {
        if (type === 'document') {
            this.emailAttach.document.isLinked = false;
            this.emailAttach.document.original = false;
        } else {
            this.emailAttach[type].splice(index, 1);
        }
    }

    switchEditionMode() {
        this.htmlMode = !this.htmlMode;
        if (this.htmlMode) {
            $('.tox-editor-header').show();
            tinymce.get('emailSignature').setContent(tinymce.get('emailSignature').getContent());
        } else {
            const dialogRef = this.dialog.open(ConfirmComponent, { panelClass: 'maarch-modal', autoFocus: false, disableClose: true, data: { title: this.translate.instant('lang.switchInPlainText'), msg: this.translate.instant('lang.confirmSwitchInPlanText') } });
            dialogRef.afterClosed().pipe(
                tap((data: string) => {
                    if (data === 'ok') {
                        $('.tox-editor-header').hide();
                        tinymce.get('emailSignature').setContent(tinymce.get('emailSignature').getContent({ format: 'text' }));
                    } else {
                        this.htmlMode = !this.htmlMode;
                    }
                })
            ).subscribe();

        }
    }

    formatEmail() {
        let objAttach: any = {};
        Object.keys(this.emailAttach).forEach(element => {
            if (!this.functions.empty(this.emailAttach[element])) {
                if (element === 'document') {
                    objAttach = {
                        id: this.emailAttach[element].id,
                        isLinked: this.emailAttach[element].isLinked,
                        original: this.emailAttach[element].original
                    };
                } else if (element === 'notes') {
                    objAttach[element] = this.emailAttach[element].map((item: any) => item.id);
                } else {
                    objAttach[element] = this.emailAttach[element].map((item: any) => {
                        return {
                            id: item.id,
                            original: item.original
                        };
                    });
                }
            }
        });

        const formatSender = {
            email: this.currentSender.email,
            entityId: !this.functions.empty(this.currentSender.entityId) ? this.currentSender.entityId : null
        };

        return {
            document: objAttach,
            sender: formatSender,
            recipients: this.recipients.map(recipient => recipient.email),
            cc: this.showCopies ? this.copies.map(copy => copy.email) : [],
            cci: this.showInvisibleCopies ? this.invisibleCopies.map((invCopy => invCopy.email)) : [],
            object: this.emailsubject,
            body: this.htmlMode ? tinymce.get('emailSignature').getContent() : tinymce.get('emailSignature').getContent({ format: 'text' }),
            isHtml: true,
            status: this.emailStatus
        };
    }

    isSelectedAttachMail(item: any, type: string) {
        if (type === 'document') {
            return this.emailAttach.document.isLinked;
        } else {
            return this.emailAttach[type].filter((attach: any) => attach.id === item.id).length > 0;
        }
    }

    canManageMail() {
        if ((this.data.emailId === null) || (this.emailStatus !== 'SENT' && this.headerService.user.id === this.emailCreatorId)) {
            this.recipientsInput.enable();
            return true;
        } else {
            this.recipientsInput.disable();
            return false;
        }
    }

    isAllEmailRightFormat() {
        let state = true;
        const allEmail = this.recipients.concat(this.copies).concat(this.invisibleCopies);

        allEmail.map(item => item.email).forEach(email => {
            if (this.isBadEmailFormat(email)) {
                state = false;
            }
        });

        return state;
    }

    compareSenders(sender1: any, sender2: any) {
        return (sender1.label === sender2.label || ((sender1.label === null || sender2.label === null) && (sender1.entityId === null || sender2.entityId === null))) && sender1.entityId === sender2.entityId && sender1.email === sender2.email;
    }


    b64DecodeUnicode(str: string) {
        // Going backwards: from bytestream, to percent-encoding, to original string.
        return decodeURIComponent(atob(str).split('').map(function (c) {
            return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
        }).join(''));
    }

    onPaste(event: ClipboardEvent, type: string) {
        const clipboardData = event.clipboardData;
        const pastedText = clipboardData.getData('text');
        this.formatEmailAddress(pastedText, type);
    }

    formatEmailAddress(rawAddresses: string, type: string) {
        const arrRawAdd: string[] = rawAddresses.split(/[,;]+/);

        if (!this.functions.empty(arrRawAdd)) {

            setTimeout(() => {
                this.recipientsInput.setValue(null);

                this[type + 'Field'].nativeElement.value = '';
            }, 0);

            arrRawAdd.forEach((rawAddress: any) => {
                rawAddress = rawAddress.match(/([a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z0-9_-]+)/gi);

                if (!this.functions.empty(rawAddress)) {
                    this[type].push({ label: rawAddress[0], email: rawAddress[0] });
                }
            });
        }
    }

    openSummarySheetModal(keyVal: any): void {
        if (keyVal !== 'summarySheet') {
            return;
        }
        let today: any;
        let dd: any;
        let mm: any;
        let yyyy: any;

        today = new Date();
        dd = today.getDate();
        mm = today.getMonth() + 1;
        yyyy = today.getFullYear();

        if (dd < 10) {
            dd = '0' + dd;
        }
        if (mm < 10) {
            mm = '0' + mm;
        }
        today = dd + '-' + mm + '-' + yyyy;
        const title = this.translate.instant('lang.summarySheet') + ' ' + today;

        const dialogRef = this.dialog.open(SummarySheetComponent, {
            panelClass: 'maarch-full-height-modal',
            width: '800px',
            data: {
                paramMode: true
            }
        });
        dialogRef.afterClosed().pipe(
            filter((data: string) => data !== undefined),
            tap((data: any) => {
                this.summarySheetUnits = data;
                this.emailAttach['summarySheet'].push({
                    label: title,
                    format: 'pdf',
                    title: title,
                    list: []
                });
            }),
            catchError((err: any) => {
                this.notify.handleSoftErrors(err);
                return of(false);
            })
        ).subscribe();
    }

    async createSummarySheet() {
        return new Promise(resolve => {
            this.http.post('../rest/resourcesList/summarySheets?mode=base64', {
                units:     this.summarySheetUnits,
                resources: [this.data.resId]
            })
                .pipe(
                    tap(async (sheetData: any) => {
                        await this.saveSummarySheet(sheetData.encodedDocument);

                        this.loading = false;
                        resolve(true);
                    }),
                    catchError((err) => {
                        this.notify.handleErrors(err);
                        resolve(false);
                        return of(false);
                    })
                ).subscribe();
        });
    }

    async saveSummarySheet(encodedDocument: any) {
        return new Promise(resolve => {
            let today: any;
            let dd: any;
            let mm: any;
            let yyyy: any;

            today = new Date();
            dd = today.getDate();
            mm = today.getMonth() + 1;
            yyyy = today.getFullYear();

            if (dd < 10) {
                dd = '0' + dd;
            }
            if (mm < 10) {
                mm = '0' + mm;
            }
            today = dd + '-' + mm + '-' + yyyy;
            const title = this.translate.instant('lang.summarySheet') + ' ' + today;
            this.http.post('../rest/attachments', {resIdMaster: this.data.resId, encodedFile: encodedDocument, type: 'summary_sheet', format: 'PDF', title: title})
            .pipe(
                tap((dataAttachment: any) => {
                    this.emailAttach['summarySheet'] = undefined;

                    this.emailAttach['attachments'].push({
                        id:       dataAttachment.id,
                        label:    title,
                        format:   'pdf',
                        title:    title,
                        original: true
                    });
                    this.loading = false;
                    resolve(true);
                }),
                catchError((err) => {
                    this.notify.handleErrors(err);
                    resolve(false);
                    return of(false);
                })
            )
            .subscribe();
        });
    }
}
