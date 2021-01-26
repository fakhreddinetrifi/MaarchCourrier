import { Component, OnInit, Inject, ViewChild } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { NotificationService } from '@service/notification/notification.service';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { HttpClient } from '@angular/common/http';
import { NoteEditorComponent } from '../../notes/note-editor.component';
import { tap, finalize, catchError } from 'rxjs/operators';
import { of } from 'rxjs';

@Component({
    templateUrl: "update-acknowledgement-send-date-action.component.html",
    styleUrls: ['../close-mail-action/close-mail-action.component.scss'],
})
export class UpdateAcknowledgementSendDateActionComponent implements OnInit {

    
    loading: boolean = false;

    @ViewChild('noteEditor', { static: true }) noteEditor: NoteEditorComponent;

    acknowledgementSendDate    : Date      = new Date();
    acknowledgementSendDateEnd : Date      = new Date();

    constructor(public translate: TranslateService, public http: HttpClient, private notify: NotificationService, public dialogRef: MatDialogRef<UpdateAcknowledgementSendDateActionComponent>, @Inject(MAT_DIALOG_DATA) public data: any) { }

    ngOnInit(): void { }

    onSubmit() {
        this.loading = true;
        if ( this.data.resIds.length > 0) {
            this.executeAction();
        }
    }

    executeAction() {
        this.http.put(this.data.processActionRoute, {resources : this.data.resIds, note : this.noteEditor.getNote(), data : {send_date : (this.acknowledgementSendDate.getTime() / 1000).toString()}}).pipe(
            tap(() => {
                this.dialogRef.close(this.data.resIds);
            }),
            finalize(() => this.loading = false),
            catchError((err: any) => {
                this.notify.handleSoftErrors(err);
                return of(false);
            })
        ).subscribe();
    }
}
