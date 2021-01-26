import { Component, OnInit, ViewChild, Inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { NotificationService } from '@service/notification/notification.service';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { catchError, tap, finalize } from 'rxjs/operators';
import { of } from 'rxjs';
import { LocalStorageService } from '@service/local-storage.service';
import { HeaderService } from '@service/header.service';
import { TranslateService } from '@ngx-translate/core';

@Component({
    templateUrl: 'entities-export.component.html',
    styleUrls: ['entities-export.component.scss'],
})
export class EntitiesExportComponent implements OnInit {

    loading: boolean = false;
    loadingExport: boolean = false;

    delimiters = [';', ',', 'TAB'];
    formats = ['csv'];

    exportModel: any = {
        delimiter: ';',
        format: 'csv',
    };

    exportModelList: any;

    constructor(
        public translate: TranslateService,
        public http: HttpClient,
        private notify: NotificationService,
        public dialogRef: MatDialogRef<EntitiesExportComponent>,
        @Inject(MAT_DIALOG_DATA) public data: any,
        private localStorage: LocalStorageService,
        private headerService: HeaderService
    ) { }

    async ngOnInit(): Promise<void> {
        this.setConfiguration();
    }

    exportData() {
        this.localStorage.save(`exportEntities_${this.headerService.user.id}`, JSON.stringify(this.exportModel));
        this.loadingExport = true;
        this.http.put('../rest/entities/export', this.exportModel, { responseType: 'blob' }).pipe(
            tap((data: any) => {
                if (data.type !== 'text/html') {
                    const downloadLink = document.createElement('a');
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
                    downloadLink.setAttribute('download', 'export_entities_maarch_' + today + '.' + this.exportModel.format.toLowerCase());
                    document.body.appendChild(downloadLink);
                    downloadLink.click();
                    this.dialogRef.close();
                } else {
                    alert(this.translate.instant('lang.tooMuchDatas'));
                }
            }),
            finalize(() => this.loadingExport = false),
            catchError((err: any) => {
                this.notify.handleSoftErrors(err);
                return of(false);
            })
        ).subscribe();
    }

    setConfiguration() {
        if (this.localStorage.get(`exportEntitiesFields_${this.headerService.user.id}`) !== null) {
            this.exportModel.delimiter = JSON.parse(this.localStorage.get(`exportEntitiesFields_${this.headerService.user.id}`)).delimiter;
        }
    }
}
