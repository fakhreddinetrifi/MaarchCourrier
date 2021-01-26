import { Component, OnInit, Inject, ViewChild } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { NotificationService } from '@service/notification/notification.service';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { HttpClient } from '@angular/common/http';
import { NoteEditorComponent } from '../../notes/note-editor.component';
import { tap, finalize, catchError } from 'rxjs/operators';
import { of } from 'rxjs';
import { FunctionsService } from '@service/functions.service';
import { AvisWorkflowComponent } from '../../avis/avis-workflow.component';

@Component({
    templateUrl: "continue-avis-circuit-action.component.html",
    styleUrls: ['continue-avis-circuit-action.component.scss'],
})
export class ContinueAvisCircuitActionComponent implements OnInit {

    
    loading: boolean = false;

    resourcesWarnings: any[] = [];
    resourcesErrors: any[] = [];

    ownerOpinion: string = '';
    opinionContent: string = '';

    opinionLimitDate: string | Date = null;

    noResourceToProcess: boolean = null;

    @ViewChild('noteEditor', { static: true }) noteEditor: NoteEditorComponent;
    @ViewChild('appAvisWorkflow', { static: false }) appAvisWorkflow: AvisWorkflowComponent;

    constructor(
        public translate: TranslateService,
        public http: HttpClient, 
        private notify: NotificationService, 
        public dialogRef: MatDialogRef<ContinueAvisCircuitActionComponent>, 
        @Inject(MAT_DIALOG_DATA) public data: any,
        public functions: FunctionsService) { }

    ngOnInit() {
        this.checkAvisCircuit();
    }

    checkAvisCircuit() {
        this.loading = true;
        this.resourcesErrors = [];
        this.resourcesWarnings = [];
        this.http.post('../rest/resourcesList/users/' + this.data.userId + '/groups/' + this.data.groupId + '/baskets/' + this.data.basketId + '/actions/' + this.data.action.id + '/checkContinueOpinionCircuit', { resources: this.data.resIds }).pipe(
            tap((data: any) => {
                if (!this.functions.empty(data.resourcesInformations.warning)) {
                    this.resourcesWarnings = data.resourcesInformations.warning;
                }

                if (!this.functions.empty(data.resourcesInformations.error)) {
                    this.resourcesErrors = data.resourcesInformations.error;
                    this.noResourceToProcess = this.resourcesErrors.length === this.data.resIds.length;
                }

                if (!this.noResourceToProcess) {
                    this.ownerOpinion = data.resourcesInformations.success[0].avisUserAsk;
                    this.opinionContent = data.resourcesInformations.success[0].note;
                    this.opinionLimitDate = new Date(data.resourcesInformations.success[0].opinionLimitDate);
                    this.opinionLimitDate = this.functions.formatDateObjectToDateString(this.opinionLimitDate);
                }
            }),
            finalize(() => this.loading = false),
            catchError((err: any) => {
                this.notify.handleSoftErrors(err);
                this.dialogRef.close();
                return of(false);
            })
        ).subscribe();
    }

    onSubmit() {
        const realResSelected: number[] = this.data.resIds.filter((resId: any) => this.resourcesErrors.map(resErr => resErr.res_id).indexOf(resId) === -1);
        this.executeAction(realResSelected);
    }

    executeAction(realResSelected: number[]) {
        const noteContent: string = `[${this.translate.instant('lang.avisUserState')}] ${this.noteEditor.getNoteContent()}`;
        this.noteEditor.setNoteContent(noteContent);
        this.http.put(this.data.processActionRoute, {resources : realResSelected, note: this.noteEditor.getNote()}).pipe(
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
        return !this.noResourceToProcess && !this.functions.empty(this.noteEditor.getNoteContent());
    }
}
