import { Component, OnInit } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { HeaderService } from '@service/header.service';
import { AppService } from '@service/app.service';
import { environment } from '../environments/environment';
import { catchError, tap } from 'rxjs/operators';
import { HttpClient } from '@angular/common/http';
import { NotificationService } from '@service/notification/notification.service';
import { of } from 'rxjs';

@Component({
    templateUrl: 'about-us.component.html',
    styleUrls: ['about-us.component.css']
})
export class AboutUsComponent implements OnInit {

    applicationVersion: string;


    loading: boolean = false;

    commitHash: string = this.translate.instant('lang.undefined');

    constructor(
        public translate: TranslateService,
        public http: HttpClient,
        private notify: NotificationService,
        private headerService: HeaderService,
        public appService: AppService) { }

    async ngOnInit() {
        this.headerService.setHeader(this.translate.instant('lang.aboutUs'));

        this.applicationVersion = environment.VERSION;
        this.loading = false;

        await this.loadCommitInformation();
    }

    loadCommitInformation() {
        return new Promise((resolve) => {
            this.http.get('../rest/commitInformation').pipe(
                tap((data: any) => {
                    this.commitHash = data.hash !== null ? data.hash : this.translate.instant('lang.undefined');
                    resolve(true);
                }),
                catchError((err: any) => {
                    this.notify.handleSoftErrors(err);
                    return of(false);
                })
            ).subscribe();
        });
    }
}

