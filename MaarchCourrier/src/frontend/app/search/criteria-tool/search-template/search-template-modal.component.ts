import { Component, Inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { TranslateService } from '@ngx-translate/core';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { NotificationService } from '@service/notification/notification.service';
import { tap, catchError } from 'rxjs/operators';
import { of } from 'rxjs';

@Component({
    templateUrl: 'search-template-modal.component.html',
    styleUrls: ['search-template-modal.component.scss'],
})
export class AddSearchTemplateModalComponent {

    constructor(
        public translate: TranslateService,
        public http: HttpClient,
        @Inject(MAT_DIALOG_DATA) public data: any,
        public dialogRef: MatDialogRef<AddSearchTemplateModalComponent>,
        private notify: NotificationService) {
    }

    onSubmit() {
        this.http.post('../rest/searchTemplates', this.data.searchTemplate).pipe(
            tap((data: any) => {
                this.data.searchTemplate.id = data.id;
                this.notify.success(this.translate.instant('lang.searchTemplateAdded'));
                this.dialogRef.close(this.data);
            }),
            catchError((err: any) => {
                this.notify.handleErrors(err);
                return of(false);
            })
        ).subscribe();
    }
}
