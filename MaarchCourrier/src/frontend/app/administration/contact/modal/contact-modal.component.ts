import { Component, Inject, ViewChild, Renderer2, OnInit } from '@angular/core';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { TranslateService } from '@ngx-translate/core';
import { HttpClient } from '@angular/common/http';
import { PrivilegeService } from '@service/privileges.service';
import { HeaderService } from '@service/header.service';
import { MatSidenav } from '@angular/material/sidenav';

declare var $: any;

@Component({
    templateUrl: 'contact-modal.component.html',
    styleUrls: ['contact-modal.component.scss'],
})
export class ContactModalComponent implements OnInit{
    
    creationMode: boolean = true;
    canUpdate: boolean = false;
    contact: any = null;
    mode: 'update' | 'read' = 'read';
    loadedDocument: boolean = false;

    @ViewChild('drawer', { static: true }) drawer: MatSidenav;

    constructor(
        public translate: TranslateService,
        public http: HttpClient,
        private privilegeService: PrivilegeService,
        @Inject(MAT_DIALOG_DATA) public data: any,
        public dialogRef: MatDialogRef<ContactModalComponent>,
        public headerService: HeaderService,
        private renderer: Renderer2) {
    }

    ngOnInit(): void {
        if (this.data.contactId !== null) {
            this.contact = {
                id: this.data.contactId,
                type: this.data.contactType
            };
            this.creationMode = false;
        } else {
            this.creationMode = true;
            this.mode = 'update';
            if (this.mode === 'update') {
                $('.maarch-modal').css({ 'height': '99vh' });
                $('.maarch-modal').css({ 'width': '99vw' });
            }
            if (this.headerService.getLastLoadedFile() !== null) {
                this.drawer.toggle();
                setTimeout(() => {
                    this.loadedDocument = true;
                }, 200);
            }
        }
        this.canUpdate = this.privilegeService.hasCurrentUserPrivilege('update_contacts');
    }

    switchMode() {
        this.mode = this.mode === 'read' ? 'update' : 'read';
        if (this.mode === 'update') {
            $('.maarch-modal').css({ 'height': '99vh' });
            $('.maarch-modal').css({ 'width': '99vw' });
        }

        if (this.headerService.getLastLoadedFile() !== null) {
            this.drawer.toggle();
            setTimeout(() => {
                this.loadedDocument = true;
            }, 200);
        }
    }
}
