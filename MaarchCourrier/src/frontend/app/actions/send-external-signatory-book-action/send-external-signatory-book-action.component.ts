import { Component, OnInit, Inject, ViewChild, ChangeDetectorRef } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { NotificationService } from '@service/notification/notification.service';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { HttpClient } from '@angular/common/http';
import { NoteEditorComponent } from '../../notes/note-editor.component';
import { XParaphComponent } from './x-paraph/x-paraph.component';
import { MaarchParaphComponent } from './maarch-paraph/maarch-paraph.component';
import { FastParaphComponent } from './fast-paraph/fast-paraph.component';
import { IParaphComponent } from './i-paraph/i-paraph.component';
import { IxbusParaphComponent } from './ixbus-paraph/ixbus-paraph.component';
import { tap, finalize, catchError } from 'rxjs/operators';
import { of } from 'rxjs';

@Component({
    templateUrl: "send-external-signatory-book-action.component.html",
    styleUrls: ['send-external-signatory-book-action.component.scss'],
})
export class SendExternalSignatoryBookActionComponent implements OnInit {

    
    loading: boolean = false;

    additionalsInfos: any = {
        destinationId: '',
        users: [],
        attachments: [],
        noAttachment: []
    };
    resourcesMailing: any[] = [];
    signatoryBookEnabled: string = '';

    externalSignatoryBookDatas: any = {
        steps: [],
        objectSent: 'attachment'
    };

    integrationsInfo: any = {
        inSignatureBook: {
            icon: 'fas fa-file-signature'
        }
    };

    errors: any;

    @ViewChild('noteEditor', { static: true }) noteEditor: NoteEditorComponent;
    
    @ViewChild('xParaph', { static: false }) xParaph: XParaphComponent;
    @ViewChild('maarchParapheur', { static: false }) maarchParapheur: MaarchParaphComponent;
    @ViewChild('fastParapheur', { static: false }) fastParapheur: FastParaphComponent;
    @ViewChild('iParapheur', { static: false }) iParapheur: IParaphComponent;
    @ViewChild('ixbus', { static: false }) ixbus: IxbusParaphComponent;

    constructor(
        public translate: TranslateService,
        public http: HttpClient,
        private notify: NotificationService,
        public dialogRef: MatDialogRef<SendExternalSignatoryBookActionComponent>,
        @Inject(MAT_DIALOG_DATA) public data: any,
        private changeDetectorRef: ChangeDetectorRef) { }

    ngOnInit(): void {
        this.loading = true;

        this.checkExternalSignatureBook();
    }

    onSubmit() {
        this.loading = true;
        if ( this.data.resIds.length > 0) {
            this.executeAction();
        }
    }

    checkExternalSignatureBook() {
        this.loading = true;
        
        return new Promise((resolve, reject) => {
            this.http.post(`../rest/resourcesList/users/${this.data.userId}/groups/${this.data.groupId}/baskets/${this.data.basketId}/checkExternalSignatoryBook`, { resources: this.data.resIds }).pipe(
                tap((data: any) => {
                    this.additionalsInfos = data.additionalsInfos;
                    if (this.additionalsInfos.attachments.length > 0) {
                        this.signatoryBookEnabled = data.signatureBookEnabled;
                        data.additionalsInfos.attachments.forEach((value: any) => {
                            if (value.mailing) {
                                this.resourcesMailing.push(value);
                            }
                        });
                    }
                    this.errors = data.errors;
                    resolve(true);
                }),
                finalize(() => this.loading = false),
                catchError((err: any) => {
                    this.notify.handleSoftErrors(err);
                    this.dialogRef.close();
                    return of(false);
                })
            ).subscribe();
        });
    }

    executeAction() {
        let realResSelected: string[];
        let datas: any;

        realResSelected = this[this.signatoryBookEnabled].getRessources();
        datas = this[this.signatoryBookEnabled].getDatas();
        
        this.http.put(this.data.processActionRoute, {resources : realResSelected, note : this.noteEditor.getNote(), data: datas}).pipe(
            tap((data: any) => {
                if (!data) {
                    this.dialogRef.close(realResSelected);
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

    isValidAction() {
        if (this[this.signatoryBookEnabled] !== undefined) {   
            return this[this.signatoryBookEnabled].isValidParaph();
        } else {
            return false;
        }
    }

    toggleIntegration(integrationId: string) {
        this.http.put(`../rest/resourcesList/integrations`, {resources : this.data.resIds, integrations : { [integrationId] : !this.data.resource.integrations[integrationId]}}).pipe(
            tap(async () => {
                this.data.resource.integrations[integrationId] = !this.data.resource.integrations[integrationId];
                await this.checkExternalSignatureBook();
                this.changeDetectorRef.detectChanges();
            }),
            catchError((err: any) => {
                this.notify.handleSoftErrors(err);
                return of(false);
            })
        ).subscribe();
    }


}
