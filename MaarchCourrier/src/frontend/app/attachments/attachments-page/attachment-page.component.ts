import { Component, OnInit, Inject, ViewChild } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { TranslateService } from '@ngx-translate/core';
import { catchError, tap, finalize, exhaustMap, filter } from 'rxjs/operators';
import { of } from 'rxjs';
import { NotificationService } from '@service/notification/notification.service';
import { MatDialog, MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { AppService } from '@service/app.service';
import { SortPipe } from '../../../plugins/sorting.pipe';
import { FormControl, Validators, FormGroup } from '@angular/forms';
import { DocumentViewerComponent } from '../../viewer/document-viewer.component';
import { PrivilegeService } from '@service/privileges.service';
import { HeaderService } from '@service/header.service';
import { ConfirmComponent } from '../../../plugins/modal/confirm.component';
import { FunctionsService } from '@service/functions.service';

@Component({
    selector: 'app-attachment-page',
    templateUrl: 'attachment-page.component.html',
    styleUrls: [
        'attachment-page.component.scss',
        '../../indexation/indexing-form/indexing-form.component.scss'
    ],
    providers: [SortPipe],
})

export class AttachmentPageComponent implements OnInit {

    loading: boolean = true;
    sendMassMode: boolean = false;
    sendingData: boolean = false;

    attachmentsTypes: any[] = [];
    attachment: any;

    versions: any[] = [];
    hidePanel: boolean = false;
    newVersion: boolean = false;

    attachFormGroup: FormGroup = null;

    editMode: boolean = false;

    now: Date = new Date();

    @ViewChild('appAttachmentViewer', { static: false }) appAttachmentViewer: DocumentViewerComponent;

    constructor(
        public translate: TranslateService,
        public http: HttpClient,
        @Inject(MAT_DIALOG_DATA) public data: any,
        public dialog: MatDialog,
        public dialogRef: MatDialogRef<AttachmentPageComponent>,
        public appService: AppService,
        private notify: NotificationService,
        private sortPipe: SortPipe,
        public headerService: HeaderService,
        public privilegeService: PrivilegeService,
        public functions: FunctionsService) {
    }

    async ngOnInit(): Promise<void> {
        this.hidePanel = this.data.hidePanel !== undefined ? this.data.hidePanel : false;

        await this.loadAttachmentTypes();
        await this.loadAttachment();

        this.loading = false;
    }

    loadAttachmentTypes() {
        return new Promise((resolve) => {
            this.http.get('../rest/attachmentsTypes').pipe(
                tap((data: any) => {
                    Object.keys(data.attachmentsTypes).forEach(templateType => {
                        this.attachmentsTypes.push({
                            id: templateType,
                            ...data.attachmentsTypes[templateType]
                        });
                    });
                    this.attachmentsTypes = this.sortPipe.transform(this.attachmentsTypes, 'label');
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

    loadAttachment() {
        return new Promise((resolve) => {
            this.http.get(`../rest/attachments/${this.data.resId}`).pipe(
                tap((data: any) => {
                    let contact: any = null;

                    if (!this.functions.empty(this.data.editMode)) {
                        this.editMode = this.data.editMode;
                    } else if ((this.privilegeService.hasCurrentUserPrivilege('manage_attachments') || this.headerService.user.id === data.typist) && data.status !== 'SIGN' && data.status !== 'FRZ') {
                        this.editMode = true;
                    }

                    if (data.type === 'acknowledgement_record_management' || data.type === 'reply_record_management') {
                        this.editMode = false;
                    }

                    if (data.recipientId !== null && data.status !== 'SEND_MASS') {
                        contact = [{
                            id: data.recipientId,
                            type: data.recipientType
                        }];
                    }

                    this.sendMassMode = data.status === 'SEND_MASS';

                    this.attachment = {
                        typist: new FormControl({ value: data.typist, disabled: true }, [Validators.required]),
                        typistLabel: new FormControl({ value: data.typistLabel, disabled: true }, [Validators.required]),
                        creationDate: new FormControl({ value: data.creationDate, disabled: true }, [Validators.required]),
                        modificationDate: new FormControl({ value: data.modificationDate, disabled: true }),
                        modifiedBy: new FormControl({ value: data.modifiedBy, disabled: true }),
                        signatory: new FormControl({ value: data.signatory, disabled: true }),
                        signatoryId: new FormControl({ value: data.signatoryId, disabled: true }),
                        signDate: new FormControl({ value: data.signDate, disabled: true }),
                        resId: new FormControl({ value: this.data.resId, disabled: true }, [Validators.required]),
                        chrono: new FormControl({ value: data.chrono, disabled: true }),
                        originId: new FormControl({ value: data.originId, disabled: true }),
                        resIdMaster: new FormControl({ value: data.resIdMaster, disabled: true }, [Validators.required]),
                        status: new FormControl({ value: data.status, disabled: true }, [Validators.required]),
                        relation: new FormControl({ value: data.relation, disabled: true }, [Validators.required]),
                        title: new FormControl({ value: data.title, disabled: !this.editMode }, [Validators.required]),
                        recipient: new FormControl({ value: contact, disabled: !this.editMode }),
                        type: new FormControl({ value: data.type, disabled: !this.editMode }, [Validators.required]),
                        validationDate: new FormControl({ value: data.validationDate !== null ? new Date(data.validationDate) : null, disabled: !this.editMode }),
                        signedResponse: new FormControl({ value: data.signedResponse, disabled: false }),
                        encodedFile: new FormControl({ value: '_CURRENT_FILE', disabled: !this.editMode }, [Validators.required]),
                        format: new FormControl({ value: data.format, disabled: true }, [Validators.required])
                    };

                    this.versions = data.versions;

                    this.attachFormGroup = new FormGroup(this.attachment);
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

    createNewVersion(mode: string = 'default') {
        this.sendingData = true;
        this.appAttachmentViewer.getFile().pipe(
            tap((data) => {
                this.attachment.encodedFile.setValue(data.content);
                this.attachment.format.setValue(data.format);
                if (this.functions.empty(this.attachment.encodedFile.value)) {
                    this.notify.error(this.translate.instant('lang.mustEditAttachmentFirst'));
                    this.sendingData = false;
                }
            }),
            filter(() => !this.functions.empty(this.attachment.encodedFile.value)),
            exhaustMap(() => this.http.post(`../rest/attachments`, this.getAttachmentValues(true, mode))),
            tap(async (data: any) => {
                if (this.sendMassMode && mode === 'mailing') {
                    await this.generateMailling(data.id);
                    this.notify.success(this.translate.instant('lang.attachmentGenerated'));
                } else {
                    this.notify.success(this.translate.instant('lang.newVersionAdded'));
                }
                this.dialogRef.close('success');
            }),
            finalize(() => this.sendingData = false),
            catchError((err: any) => {
                this.notify.handleSoftErrors(err);
                this.dialogRef.close('');
                return of(false);
            })
        ).subscribe();
    }

    updateAttachment(mode: string = 'default') {

        this.sendingData = true;
        this.appAttachmentViewer.getFile().pipe(
            tap((data) => {
                this.attachment.encodedFile.setValue(data.content);
                this.attachment.format.setValue(data.format);
            }),
            exhaustMap(() => this.http.put(`../rest/attachments/${this.attachment.resId.value}`, this.getAttachmentValues(false, mode))),
            tap(async () => {
                if (this.sendMassMode && mode === 'mailing') {
                    await this.generateMailling(this.attachment.resId.value);
                    this.notify.success(this.translate.instant('lang.attachmentGenerated'));
                } else {
                    this.notify.success(this.translate.instant('lang.attachmentUpdated'));
                }
                this.dialogRef.close('success');
            }),
            finalize(() => this.sendingData = false),
            catchError((err: any) => {
                this.notify.handleSoftErrors(err);
                this.dialogRef.close('');
                return of(false);
            })
        ).subscribe();
    }

    generateMailling(resId: number) {
        return new Promise((resolve) => {
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

    enableForm(state: boolean) {
        Object.keys(this.attachment).forEach(element => {
            if (['status', 'typistLabel', 'creationDate', 'relation', 'modificationDate', 'modifiedBy'].indexOf(element) === -1) {

                if (state) {
                    this.attachment[element].enable();
                } else {
                    this.attachment[element].disable();
                }
            }

        });
    }

    getAttachmentValues(newAttachment: boolean = false, mode: string) {
        const attachmentValues = {};
        Object.keys(this.attachment).forEach(element => {
            if (this.attachment[element] !== undefined && (this.attachment[element].value !== null && this.attachment[element].value !== undefined)) {
                if (element === 'validationDate') {
                    const day = this.attachment[element].value.getDate();
                    const month = this.attachment[element].value.getMonth() + 1;
                    const year = this.attachment[element].value.getFullYear();
                    attachmentValues[element] = ('00' + day).slice(-2) + '-' + ('00' + month).slice(-2) + '-' + year + ' 23:59:59';
                } else if (element === 'recipient') {
                    attachmentValues['recipientId'] = this.attachment[element].value.length > 0 ? this.attachment[element].value[0].id : null;
                    attachmentValues['recipientType'] = this.attachment[element].value.length > 0 ? this.attachment[element].value[0].type : null;
                } else {
                    attachmentValues[element] = this.attachment[element].value;
                }
                if (element === 'encodedFile') {
                    if (this.attachment[element].value === '_CURRENT_FILE') {
                        attachmentValues['encodedFile'] = null;
                    }
                    // attachmentValues['format'] = this.appAttachmentViewer.getFile().format;
                }
                if (mode === 'mailing') {
                    attachmentValues['inMailing'] = true;
                }
            }
        });

        if (newAttachment) {
            attachmentValues['originId'] = this.attachment['originId'].value !== null ? this.attachment['originId'].value : attachmentValues['resId'];

            attachmentValues['relation'] = this.attachment['relation'].value + 1;
            delete attachmentValues['resId'];
        }

        return attachmentValues;
    }

    setDatasViewer(ev: any) {
        const datas: any = {};
        Object.keys(this.attachment).forEach(element => {
            if (['title', 'validationDate', 'effectiveDate'].indexOf(element) > -1) {
                datas['attachment_' + element] = this.attachment[element].value;
            }
        });
        if (ev === 'setData') {
            this.appAttachmentViewer.setDatas(datas);
        } else if (ev === 'cleanFile') {
            this.attachment['encodedFile'].setValue(null);
        } else {
            datas['resId'] = this.attachment['resIdMaster'].value;
            // this.attachment.encodedFile.setValue(this.appAttachmentViewer.getFile().content);
            this.appAttachmentViewer.setDatas(datas);
            // this.setNewVersion();
        }
    }

    getAttachType(attachType: any) {
        this.appAttachmentViewer.loadTemplatesByResId(this.attachment['resIdMaster'].value, attachType);
    }

    setNewVersion() {
        if (!this.newVersion) {
            const dialogRef = this.dialog.open(ConfirmComponent, { panelClass: 'maarch-modal', autoFocus: false, disableClose: true, data: { title: this.translate.instant('lang.createNewVersion'), msg: this.translate.instant('lang.confirmAction') } });

            dialogRef.afterClosed().pipe(
                filter((data: string) => data === 'ok'),
                tap(() => {
                    this.newVersion = true;
                }),
                catchError((err: any) => {
                    this.notify.handleErrors(err);
                    return of(false);
                })
            ).subscribe();
        }

    }

    deleteSignedVersion() {
        const dialogRef = this.dialog.open(ConfirmComponent, { panelClass: 'maarch-modal', autoFocus: false, disableClose: true, data: { title: this.translate.instant('lang.deleteSignedVersion'), msg: this.translate.instant('lang.confirmAction') } });

        dialogRef.afterClosed().pipe(
            filter((data: string) => data === 'ok'),
            exhaustMap(() => this.http.put(`../rest/attachments/${this.attachment['resId'].value}/unsign`, {})),
            tap(() => {
                this.attachment.status.setValue('A_TRA');
                this.attachment.signedResponse.setValue(null);
                if (this.privilegeService.hasCurrentUserPrivilege('manage_attachments') || this.headerService.user.id === this.attachment['typist'].value) {
                    this.editMode = true;
                    this.enableForm(this.editMode);
                }
                this.notify.success(this.translate.instant('lang.signedVersionDeleted'));
                this.dialogRef.close('success');
            }),
            catchError((err: any) => {
                this.notify.handleErrors(err);
                return of(false);
            })
        ).subscribe();
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

    isEditing() {
        if (this.functions.empty(this.appAttachmentViewer)) {
            return false;
        }

        return this.appAttachmentViewer.isEditorLoaded();
    }

    closeModal() {

        if (this.appAttachmentViewer.isEditingTemplate()) {
            const dialogRef = this.dialog.open(ConfirmComponent, { panelClass: 'maarch-modal', autoFocus: false, disableClose: true, data: { title: this.translate.instant('lang.close'), msg: this.translate.instant('lang.editingDocumentMsg') } });

            dialogRef.afterClosed().pipe(
                filter((data: string) => data === 'ok'),
                tap(() => {
                    this.dialogRef.close();
                }),
                catchError((err: any) => {
                    this.notify.handleErrors(err);
                    return of(false);
                })
            ).subscribe();
        } else {
            this.dialogRef.close();
        }
    }
}
