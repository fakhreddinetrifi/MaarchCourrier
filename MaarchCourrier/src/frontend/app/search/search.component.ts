import { Component, OnInit, ViewChild, EventEmitter, ViewContainerRef, TemplateRef } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { MatDialogRef } from '@angular/material/dialog';
import { MatSidenav } from '@angular/material/sidenav';
import { ActivatedRoute } from '@angular/router';
import { HeaderService } from '@service/header.service';
import { AppService } from '@service/app.service';
import { FunctionsService } from '@service/functions.service';
import { CriteriaToolComponent } from './criteria-tool/criteria-tool.component';
import { SearchResultListComponent } from './result-list/search-result-list.component';


@Component({
    templateUrl: 'search.component.html',
    styleUrls: ['search.component.scss']
})
export class SearchComponent implements OnInit {

    searchTerm: string = '';

    filtersChange = new EventEmitter();

    dialogRef: MatDialogRef<any>;
    loadingResult: boolean = false;

    @ViewChild('snav2', { static: true }) sidenavRight: MatSidenav;

    @ViewChild('adminMenuTemplate', { static: true }) adminMenuTemplate: TemplateRef<any>;
    @ViewChild('appSearchResultList', { static: false }) appSearchResultList: SearchResultListComponent;
    @ViewChild('appCriteriaTool', { static: true }) appCriteriaTool: CriteriaToolComponent;

    constructor(
        _activatedRoute: ActivatedRoute,
        public translate: TranslateService,
        private headerService: HeaderService,
        public viewContainerRef: ViewContainerRef,
        public appService: AppService,
        public functions: FunctionsService) {
        _activatedRoute.queryParams.subscribe(
            params => {
                if (!this.functions.empty(params.value)) {
                    this.searchTerm = params.value;
                }
            }
        );
    }

    ngOnInit(): void {
        this.headerService.sideBarAdmin = true;
        this.headerService.injectInSideBarLeft(this.adminMenuTemplate, this.viewContainerRef, 'adminMenu');
        this.headerService.setHeader(this.translate.instant('lang.searchMails'), '', '');
    }
}
