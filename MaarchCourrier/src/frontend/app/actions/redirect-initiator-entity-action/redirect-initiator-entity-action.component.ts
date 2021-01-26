import { Component, OnInit, Inject, ViewChild } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { NotificationService } from '@service/notification/notification.service';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { HttpClient } from '@angular/common/http';
import { NoteEditorComponent } from '../../notes/note-editor.component';
import { tap, finalize, catchError } from 'rxjs/operators';
import { of } from 'rxjs';

@Component({
    templateUrl: "redirect-initiator-entity-action.component.html",
    styleUrls: ['redirect-initiator-entity-action.component.scss'],
})
export class redirectInitiatorEntityActionComponent implements OnInit {

    
    loading: boolean = false;
    loadingInit: boolean = false;
    resourcesInfo: any = {
        withEntity: [],
        withoutEntity: []
    };

    @ViewChild('noteEditor', { static: false }) noteEditor: NoteEditorComponent;
    loadingExport: boolean;

    constructor(public translate: TranslateService, public http: HttpClient, private notify: NotificationService, public dialogRef: MatDialogRef<redirectInitiatorEntityActionComponent>, @Inject(MAT_DIALOG_DATA) public data: any) { }

    ngOnInit(): void {
        this.loadingInit = true;

        this.http.post('../rest/resourcesList/users/' + this.data.userId + '/groups/' + this.data.groupId + '/baskets/' + this.data.basketId + '/checkInitiatorEntity', { resources: this.data.resIds })
            .subscribe((data: any) => {
                this.resourcesInfo = data;
                this.loadingInit = false;
            }, (err) => {
                this.notify.error(err.error.errors);
                this.loadingInit = false;
                this.dialogRef.close();
            });
    }

    onSubmit() {
        this.loading = true;
        this.executeAction();
    }

    executeAction() {
        this.http.put(this.data.processActionRoute, { resources: this.resourcesInfo.withEntity, note: this.noteEditor.getNote() }).pipe(
            tap((data: any) => {
                if (data && data.errors != null) {
                    this.notify.error(data.errors);
                }
                this.dialogRef.close(this.resourcesInfo.withEntity);
            }),
            finalize(() => this.loading = false),
            catchError((err: any) => {
                this.notify.handleSoftErrors(err);
                return of(false);
            })
        ).subscribe();
    }

}
