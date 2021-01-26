import { Component, OnInit, ViewChild } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { TranslateService } from '@ngx-translate/core';
import { HeaderService } from '@service/header.service';
import { MatDialog, MatDialogRef } from '@angular/material/dialog';
import { MatInput } from '@angular/material/input';
import { IndexingGroupModalComponent } from '../menu/menu-shortcut.component';
import { Router } from '@angular/router';
import { AppService } from '@service/app.service';
import { PrivilegeService } from '@service/privileges.service';
import { FunctionsService } from '@service/functions.service';
import { AuthService } from '@service/auth.service';
import { RegisteredMailImportComponent } from '@appRoot/registeredMail/import/registered-mail-import.component';


@Component({
    selector: 'header-right',
    styleUrls: ['header-right.component.scss'],
    templateUrl: 'header-right.component.html',
})
export class HeaderRightComponent implements OnInit {

    dialogRef: MatDialogRef<any>;
    config: any = {};
    menus: any = [];
    searchTarget: string = '';

    hideSearch: boolean = true;

    @ViewChild('searchInput', { static: false }) searchInput: MatInput;

    constructor(
        public translate: TranslateService,
        public http: HttpClient,
        public router: Router,
        public dialog: MatDialog,
        public authService: AuthService,
        public appService: AppService,
        public headerService: HeaderService,
        public functions: FunctionsService,
        public privilegeService: PrivilegeService) { }

    ngOnInit(): void {
        this.menus = this.privilegeService.getCurrentUserMenus();
    }

    gotToMenu(shortcut: any) {
        if (shortcut.id === 'indexing' && shortcut.groups.length > 1) {
            this.config = { panelClass: 'maarch-modal', data: { indexingGroups: shortcut.groups, link: shortcut.route } };
            this.dialogRef = this.dialog.open(IndexingGroupModalComponent, this.config);
        } else if (shortcut.angular === true) {
            const component = shortcut.route.split('__');

            if (component.length === 2) {
                if (component[0] === 'RegisteredMailImportComponent') {
                    this.dialog.open(RegisteredMailImportComponent, {
                        disableClose: true,
                        width: '99vw',
                        maxWidth: '99vw',
                        panelClass: 'maarch-full-height-modal'
                    });
                }
            } else {
                this.router.navigate([shortcut.route]);
            }
        } else {
            location.href = shortcut.route;
        }
    }

    showSearchInput() {
        this.hideSearch = !this.hideSearch;
        setTimeout(() => {
            this.searchInput.focus();
        }, 200);
    }

    hideSearchBar() {
        return this.router.url.split('?')[0] !== '/search';
    }

    showLogout() {
        return ['sso'].indexOf(this.authService.authMode) > -1 && this.functions.empty(this.authService.authUri) ? false : true;
    }

    goTo() {
        this.router.navigate([`/search`], { queryParams: { value: this.searchTarget } });
    }
}
