import { Component, OnInit, Inject, ViewChild } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { NotificationService } from '@service/notification/notification.service';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { HttpClient } from '@angular/common/http';
import { NoteEditorComponent } from '../../notes/note-editor.component';
import { tap, finalize, catchError } from 'rxjs/operators';
import { of } from 'rxjs';
import {FunctionsService} from '@service/functions.service';

@Component({
    templateUrl: 'print-deposit-list-action.component.html',
    styleUrls: ['print-deposit-list-action.component.scss'],
})
export class PrintDepositListActionComponent implements OnInit {

    loading: boolean = false;
    loadingInit: boolean = false;

    types: any = [];
    canGenerate: any = [];
    cannotGenerate: any = [];

    @ViewChild('noteEditor', { static: false }) noteEditor: NoteEditorComponent;
    loadingExport: boolean;

    constructor(
        public translate: TranslateService,
        public http: HttpClient,
        private notify: NotificationService,
        public dialogRef: MatDialogRef<PrintDepositListActionComponent>,
        private functions: FunctionsService,
        @Inject(MAT_DIALOG_DATA) public data: any) { }

    ngOnInit(): void {
        this.loadingInit = true;

        this.checkPrintDepositList();
    }

    checkPrintDepositList() {
        this.http.post('../rest/resourcesList/users/' + this.data.userId + '/groups/' + this.data.groupId + '/baskets/' + this.data.basketId + '/actions/' + this.data.action.id + '/checkPrintDepositList', { resources: this.data.resIds })
            .subscribe((data: any) => {
                this.types = data.types;

                this.types['2C'] = this.functions.empty(this.types['2C']) ? this.translate.instant('lang.noneItalic') : this.types['2C'];
                this.types['2D'] = this.functions.empty(this.types['2D']) ? this.translate.instant('lang.noneItalic') : this.types['2D'];
                this.types['RW'] = this.functions.empty(this.types['RW']) ? this.translate.instant('lang.noneItalic') : this.types['RW'];


                this.canGenerate = data.canGenerate;
                this.cannotGenerate = data.cannotGenerate;
                this.loadingInit = false;
            }, (err) => {
                this.notify.error(err.error.errors);
                this.dialogRef.close();
                this.loadingInit = false;
            });
    }

    onSubmit() {
        this.loading = true;
        if (this.data.resIds.length > 0) {
            this.executeAction();
        }
    }

    executeAction() {
        const downloadLink = document.createElement('a');
        this.http.put(this.data.processActionRoute, { resources: this.canGenerate, note: this.noteEditor.getNote() }).pipe(
            tap((data: any) => {
                if (data && data.errors != null) {
                    this.notify.error(data.errors);
                }

                if (!this.functions.empty(data.data.encodedFile)) {
                    downloadLink.href = `data:application/pdf;base64,${data.data.encodedFile}`;
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
                    downloadLink.setAttribute('download', 'descriptif_pli_' + today + '.pdf');
                    document.body.appendChild(downloadLink);
                    downloadLink.click();
                    this.dialogRef.close(this.canGenerate);
                }
            }),
            finalize(() => this.loading = false),
            catchError((err: any) => {
                this.notify.handleSoftErrors(err);
                return of(false);
            })
        ).subscribe();
    }
}
