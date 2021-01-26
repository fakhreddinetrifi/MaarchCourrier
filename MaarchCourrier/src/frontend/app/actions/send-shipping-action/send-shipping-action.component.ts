import { Component, OnInit, Inject, ViewChild } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { NotificationService } from '@service/notification/notification.service';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { HttpClient } from '@angular/common/http';
import { NoteEditorComponent } from '../../notes/note-editor.component';
import { catchError, finalize, tap } from 'rxjs/operators';
import { of } from 'rxjs';
import { FunctionsService } from '@service/functions.service';

@Component({
    templateUrl: "send-shipping-action.component.html",
    styleUrls: ['send-shipping-action.component.scss'],
})
export class SendShippingActionComponent implements OnInit {

    
    loading: boolean = false;

    shippings: any[] = [{
        label: '',
        description: '',
        options: {
            shapingOptions: [],
            sendMode: '',
        },
        fee: 0,
        account: {
            id: '',
            password: ''
        },
    }];

    currentShipping: any = null;

    entitiesList: string[] = [];
    attachList: any[] = [];

    mailsNotSend: any[] = [];

    integrationsInfo: any = {
        inShipping: {
            icon: 'fas fa-shipping-fast'
        }
    };
    fatalError: any = '';

    @ViewChild('noteEditor', { static: false }) noteEditor: NoteEditorComponent;

    constructor(
        public translate: TranslateService,
        public http: HttpClient, 
        private notify: NotificationService, 
        public dialogRef: MatDialogRef<SendShippingActionComponent>, 
        @Inject(MAT_DIALOG_DATA) public data: any,
        public functions: FunctionsService) { }

    ngOnInit(): void {
        this.loading = true;

        this.checkShipping();
    }

    onSubmit() {
        this.loading = true;
        if ( this.data.resIds.length > 0) {
            this.executeAction();
        }
    }

    checkShipping() {
        this.http.post(`../rest/resourcesList/users/${this.data.userId}/groups/${this.data.groupId}/baskets/${this.data.basketId}/actions/${this.data.action.id}/checkShippings`, { resources: this.data.resIds }).pipe(
            tap((data: any) => {
                if (!this.functions.empty(data.fatalError)) {
                    this.fatalError = data;
                    this.shippings = [];
                } else {
                    this.shippings    = data.shippingTemplates;
                    this.mailsNotSend = data.canNotSend;
                    this.entitiesList = data.entities;
                    this.attachList   = data.resources;
                }
            }),
            finalize(() => this.loading = false),
            catchError((err: any) => {
                this.notify.handleSoftErrors(err);
                this.dialogRef.close();
                return of(false);
            })
        ).subscribe()
    }

    executeAction() {
        let realResSelected: string[] = this.attachList.filter(attach => attach.type === 'attachment').map((e: any) => { return e.res_id_master; });

        realResSelected = realResSelected.concat(this.attachList.filter(attach => attach.type === 'mail').map((e: any) => { return e.res_id; }));

        this.http.put(this.data.processActionRoute, {resources : realResSelected, data: { shippingTemplateId: this.currentShipping.id }, note : this.noteEditor.getNote()}).pipe(
            tap((data: any) => {
                if (data && data.errors != null) {
                    this.notify.error(data.errors);
                } else {
                    this.dialogRef.close(realResSelected);
                }
            }),
            finalize(() => this.loading = false),
            catchError((err: any) => {
                this.notify.handleSoftErrors(err);
                return of(false);
            })
        ).subscribe();
    }

    toggleIntegration(integrationId: string) {
        this.http.put(`../rest/resourcesList/integrations`, {resources : this.data.resIds, integrations : { [integrationId] : !this.data.resource.integrations[integrationId]}}).pipe(
            tap(() => {
                this.data.resource.integrations[integrationId] = !this.data.resource.integrations[integrationId];
                this.checkShipping();
            }),
            catchError((err: any) => {
                this.notify.handleSoftErrors(err);
                return of(false);
            })
        ).subscribe();
    }

}
