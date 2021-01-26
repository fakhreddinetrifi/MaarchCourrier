import { Component, OnInit, Input, ViewChild } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { TranslateService } from '@ngx-translate/core';
import { MatAutocompleteTrigger } from '@angular/material/autocomplete';
import { MatDialog } from '@angular/material/dialog';
import { MatSidenav } from '@angular/material/sidenav';
import { Observable } from 'rxjs';
import { ExportComponent } from '../export/export.component';
import { SummarySheetComponent } from '../summarySheet/summary-sheet.component';


export interface StateGroup {
    letter: string;
    names: any[];
}

@Component({
    selector: 'app-tools-list',
    templateUrl: 'tools-list.component.html',
    styleUrls: ['tools-list.component.scss'],
})
export class ToolsListComponent implements OnInit {

    @ViewChild(MatAutocompleteTrigger, { static: true }) autocomplete: MatAutocompleteTrigger;

    priorities: any[] = [];
    categories: any[] = [];
    entitiesList: any[] = [];
    statuses: any[] = [];
    metaSearchInput: string = '';

    stateGroups: StateGroup[] = [];
    stateGroupOptions: Observable<StateGroup[]>;

    isLoading: boolean = false;

    @Input('listProperties') listProperties: any;
    @Input('currentBasketInfo') currentBasketInfo: any;

    @Input('snavR') sidenavRight: MatSidenav;
    @Input('selectedRes') selectedRes: any;
    @Input('totalRes') totalRes: number;

    constructor(public translate: TranslateService, public http: HttpClient, public dialog: MatDialog) { }

    ngOnInit(): void {

    }

    openExport(): void {
        this.dialog.open(ExportComponent, {
            panelClass: 'maarch-modal',
            width: '800px',
            data: {
                selectedRes: this.selectedRes
            }
        });
    }

    openSummarySheet(): void {
        this.dialog.open(SummarySheetComponent, {
            panelClass: 'maarch-full-height-modal',
            width: '800px',
            data: {
                selectedRes: this.selectedRes
            }
        });
    }
}
