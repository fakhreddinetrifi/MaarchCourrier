import { Component, OnInit, Input } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { HeaderService } from '@service/header.service';
import { MatDialogRef } from '@angular/material/dialog';
import { MatSidenav } from '@angular/material/sidenav';

@Component({
    selector: 'header-left',
    styleUrls: ['header-left.component.scss'],
    templateUrl: "header-left.component.html",
})
export class HeaderLeftComponent implements OnInit {

    
    dialogRef: MatDialogRef<any>;
    config: any = {};

    @Input() snavLeft: MatSidenav;

    constructor(
        public translate: TranslateService,
        public headerService: HeaderService
    ) { }

    ngOnInit(): void { }
}