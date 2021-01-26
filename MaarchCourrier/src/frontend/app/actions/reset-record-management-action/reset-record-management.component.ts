import { HttpClient } from '@angular/common/http';
import { Component, Inject, OnInit, ViewChild } from '@angular/core';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { NoteEditorComponent } from '@appRoot/notes/note-editor.component';
import { TranslateService } from '@ngx-translate/core';
import { FunctionsService } from '@service/functions.service';
import { NotificationService } from '@service/notification/notification.service';
import { of } from 'rxjs';
import { catchError, finalize, tap } from 'rxjs/operators';

@Component({
    selector: 'app-reset-record-management',
    templateUrl: './reset-record-management.component.html',
    styleUrls: ['./reset-record-management.component.scss']
})
export class ResetRecordManagementComponent implements OnInit {

    loading: boolean = false;
    checking: boolean = true;
    resourcesErrors: any[] = [];
    selectedRes: number[] = [];

    @ViewChild('noteEditor', { static: false }) noteEditor: NoteEditorComponent;

    constructor(
        public translate: TranslateService,
        public http: HttpClient,
        private notify: NotificationService,
        public dialogRef: MatDialogRef<ResetRecordManagementComponent>,
        @Inject(MAT_DIALOG_DATA) public data: any,
        public functions: FunctionsService
    ) { }

    ngOnInit(): void {
        this.checkReply();
    }

    checkReply() {
        this.http.post(`../rest/resourcesList/users/${this.data.userId}/groups/${this.data.groupId}/baskets/${this.data.basketId}/checkReplyRecordManagement`, { resources: this.data.resIds, resetAction: true }).pipe(
            tap((data: any) => {
                this.resourcesErrors = data.resourcesInformations.errors;
                this.selectedRes = data.resourcesInformations.success;
            }),
            finalize(() => this.checking = false),
            catchError((err: any) => {
                this.notify.handleSoftErrors(err);
                return of(false);
            })
        ).subscribe();
    }

    onSubmit() {
        this.loading = true;
        this.executeAction();
    }

    executeAction() {
        this.http.put(this.data.processActionRoute, { resources: this.selectedRes, note: this.noteEditor.getNote(), data: {resetAction: true} }).pipe(
            tap((data: any) => {
                if (!data) {
                    this.dialogRef.close(this.selectedRes);
                }
                if (data && data.errors != null) {
                    this.notify.error(data.errors);
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
