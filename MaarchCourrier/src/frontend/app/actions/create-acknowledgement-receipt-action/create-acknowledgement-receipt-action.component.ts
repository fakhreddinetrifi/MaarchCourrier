import { Component, OnInit, Inject, ViewChild, OnDestroy } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { NotificationService } from '@service/notification/notification.service';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { HttpClient } from '@angular/common/http';
import { NoteEditorComponent } from '../../notes/note-editor.component';
import { tap, finalize, catchError } from 'rxjs/operators';
import { of } from 'rxjs';
import { FormControl } from '@angular/forms';
import { AppService } from '@service/app.service';

declare var tinymce: any;

@Component({
    templateUrl: "create-acknowledgement-receipt-action.component.html",
    styleUrls: ['create-acknowledgement-receipt-action.component.scss'],
})
export class CreateAcknowledgementReceiptActionComponent implements OnInit, OnDestroy {

    loading: boolean = false;
    loadingInit: boolean = false;

    emailsubject: string = '';
    emailContent: string = '';
    availableEmailModels: any[] = [];
    availableSignEmailModels: any[] = [];
    emailSignListForm = new FormControl();
    templateEmailListForm = new FormControl();

    acknowledgement: any = {
        alReadyGenerated: {},
        alReadySend: {},
        noSendAR: {},
        sendEmail: 0,
        sendPaper: 0,
        sendList: []
    };

    realResSelected: number[]= [];
    currentMode: string = '';

    manualAR: boolean = false;
    arMode: 'auto' | 'manual' | 'both' = 'auto';

    @ViewChild('noteEditor', { static: false }) noteEditor: NoteEditorComponent;
    loadingExport: boolean;

    constructor(
        public translate: TranslateService,
        public http: HttpClient, 
        private notify: NotificationService, 
        public dialogRef: MatDialogRef<CreateAcknowledgementReceiptActionComponent>, 
        @Inject(MAT_DIALOG_DATA) public data: any) { }

    ngOnInit(): void {
        this.loadingInit = true;

        this.checkAcknowledgementReceipt();
    }

    checkAcknowledgementReceipt() {
        this.http.post('../rest/resourcesList/users/' + this.data.userId + '/groups/' + this.data.groupId + '/baskets/' + this.data.basketId + '/actions/' + this.data.action.id + '/checkAcknowledgementReceipt?' + this.currentMode, { resources: this.data.resIds })
            .subscribe((data: any) => {
                this.acknowledgement = data;
                this.realResSelected = data.sendList;
                this.loadingInit = false;
                this.arMode = data.mode;
                this.arModeInit(this.arMode);
            }, (err) => {
                this.notify.error(err.error.errors);
                this.dialogRef.close();
                this.loadingInit = false;
            });
    }

    arModeInit(mode : string) {
        if (mode === 'manual') {
            this.toggleArManual(true);
        }
    }

    onSubmit() {
        this.loading = true;
        if (this.data.resIds.length > 0) {
            this.executeAction();
        }
    }

    ngOnDestroy(): void {
        tinymce.remove();
    }

    executeAction() {
        let data = null;
        if (this.manualAR) {
            data = {
                subject : this.emailsubject,
                content : tinymce.get('emailSignature').getContent(),
                manual  : true
            };
        }
        this.http.put(this.data.processActionRoute, { resources: this.realResSelected, note: this.noteEditor.getNote(), data }).pipe(
            tap((data: any) => {
                if (data && data.data != null) {
                    this.downloadAcknowledgementReceipt(data.data);
                }
                if (data && data.errors != null) {
                    this.notify.error(data.errors);
                }
                this.dialogRef.close(this.realResSelected);
            }),
            finalize(() => this.loading = false),
            catchError((err: any) => {
                this.notify.handleSoftErrors(err);
                return of(false);
            })
        ).subscribe();
    }

    downloadAcknowledgementReceipt(data: any) {
        this.loadingExport = true;
        this.http.post('../rest/acknowledgementReceipts', { 'resources': data }, { responseType: "blob" })
            .subscribe((data) => {
                let downloadLink = document.createElement('a');
                downloadLink.href = window.URL.createObjectURL(data);
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
                downloadLink.setAttribute('download', "acknowledgement_receipt_maarch_" + today + ".pdf");
                document.body.appendChild(downloadLink);
                downloadLink.click();
                this.loadingExport = false;
            }, (err: any) => {
                this.notify.handleErrors(err);
            });
    }

    toggleArManual(state: boolean) {
        if (state) {
            this.currentMode = 'mode=manual';
            this.checkAcknowledgementReceipt();
            this.manualAR = true;
            if (this.data.resIds.length === 1) {
                this.emailsubject = this.data.resource.subject;
                this.emailsubject = this.emailsubject.substring(0, 100);
            }
            
            this.initEmailModelsList();
            this.initSignEmailModelsList();
            setTimeout(() => {
                this.initMce();
            }, 800);
        } else {
            this.currentMode = 'mode=auto';
            this.checkAcknowledgementReceipt();
            tinymce.remove();
            this.manualAR = false;
        }
    }

    initMce() {
        tinymce.init({
            selector: "textarea#emailSignature",
            base_url: '../node_modules/tinymce/',
            readonly: false,
            suffix: '.min',
            language: this.translate.instant('lang.langISO').replace('-', '_'),
            language_url: `../node_modules/tinymce-i18n/langs/${this.translate.instant('lang.langISO').replace('-', '_')}.js`,
            menubar: false,
            statusbar: false,
            plugins: [
                'autolink', 'autoresize'
            ],
            external_plugins: {
                'maarch_b64image': "../../src/frontend/plugins/tinymce/maarch_b64image/plugin.min.js"
            },
            toolbar_sticky: true,
            toolbar_drawer: 'floating',
            toolbar: 'undo redo | fontselect fontsizeselect | bold italic underline strikethrough forecolor | maarch_b64image | \
            alignleft aligncenter alignright alignjustify \
            bullist numlist outdent indent | removeformat'
        });
    }

    mergeEmailTemplate(templateId: any) {

        this.templateEmailListForm.reset();

        this.http.post(`../rest/templates/${templateId}/mergeEmail`, { data: { resId: this.data.resIds[0] } }).pipe(
            tap((data: any) => {

                var div = document.createElement('div');

                div.innerHTML = tinymce.get('emailSignature').getContent();

                if (div.getElementsByClassName('signature').length > 0) {

                    const signatureContent = div.getElementsByClassName('signature')[0].innerHTML;

                    div.getElementsByClassName('signature')[0].remove();

                    tinymce.get('emailSignature').setContent(`${div.innerHTML}${data.mergedDocument}<div class="signature">${signatureContent}</div>`);

                } else {
                    tinymce.get('emailSignature').setContent(`${tinymce.get('emailSignature').getContent()}${data.mergedDocument}`);
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
                var div = document.createElement('div');

                div.innerHTML = tinymce.get('emailSignature').getContent();

                if (div.getElementsByClassName('signature').length > 0) {

                    div.getElementsByClassName('signature')[0].remove();

                    tinymce.get('emailSignature').setContent(`${div.innerHTML}<div class="signature">${data.emailSignature.content}</div>`);
                } else {
                    tinymce.get('emailSignature').setContent(`${tinymce.get('emailSignature').getContent()}<div class="signature">${data.emailSignature.content}</div>`);
                }
            }),
            catchError((err) => {
                this.notify.handleSoftErrors(err);
                return of(false);
            })
        ).subscribe();
    }

    initEmailModelsList() {
        if (this.availableEmailModels.length === 0 && this.data.resIds.length === 1) {
            this.http.get(`../rest/resources/${this.data.resIds[0]}/emailTemplates`).pipe(
                tap((data: any) => {
                    this.availableEmailModels = data.templates;
                }),
                catchError((err) => {
                    this.notify.handleSoftErrors(err);
                    return of(false);
                })
            ).subscribe();
        }
    }

    initSignEmailModelsList() {
        if (this.availableSignEmailModels.length === 0  && this.data.resIds.length === 1) {
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
    }
}
