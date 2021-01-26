import { Component, Inject, OnInit, ViewChild } from '@angular/core';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { TranslateService } from '@ngx-translate/core';
import { HttpClient } from '@angular/common/http';
import { NotificationService } from '@service/notification/notification.service';
import { of } from 'rxjs';
import { catchError, tap } from 'rxjs/operators';
import { SearchResultListComponent } from '@appRoot/search/result-list/search-result-list.component';

@Component({
    templateUrl: 'link-resource-modal.component.html',
    styleUrls: ['link-resource-modal.component.scss'],
})
export class LinkResourceModalComponent implements OnInit {

    searchUrl: string = '';

    @ViewChild('appSearchResultList', { static: false }) appSearchResultList: SearchResultListComponent;

    constructor(
        public translate: TranslateService,
        public http: HttpClient,
        private notify: NotificationService,
        @Inject(MAT_DIALOG_DATA) public data: any,
        public dialogRef: MatDialogRef<LinkResourceModalComponent>) {
    }

    ngOnInit(): void { }

    linkResources() {
        const selectedRes = this.appSearchResultList.getSelectedResources().filter(res => res !== this.data.resId);
        this.http.post(`../rest/resources/${this.data.resId}/linkedResources`, { linkedResources: selectedRes }).pipe(
            tap(() => {
                this.dialogRef.close('success');
            }),
            catchError((err: any) => {
                this.notify.handleSoftErrors(err);
                return of(false);
            })
        ).subscribe();

    }

    isSelectedResources() {
        return this.appSearchResultList !== undefined && this.appSearchResultList.getSelectedResources().filter(res => res !== this.data.resId).length > 0;
    }
}
