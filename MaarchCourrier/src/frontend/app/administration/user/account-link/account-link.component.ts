import { Component, Inject, OnInit } from '@angular/core';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { TranslateService } from '@ngx-translate/core';
import { HttpClient } from '@angular/common/http';
import { NotificationService } from '@service/notification/notification.service';

@Component({
    templateUrl: 'account-link.component.html',
    styleUrls: ['account-link.component.scss'],
})
export class AccountLinkComponent implements OnInit {
    
    externalUser: any = {
        inMaarchParapheur: false,
        login: '',
        firstname: '',
        lastname: '',
        email: '',
        picture: ''
    };

    constructor(
        public translate: TranslateService,
        public http: HttpClient,
        @Inject(MAT_DIALOG_DATA) public data: any,
        public dialogRef: MatDialogRef<AccountLinkComponent>,
        private notify: NotificationService) {
    }

    ngOnInit(): void {
        this.http.get('../rest/autocomplete/maarchParapheurUsers', { params: { 'search': this.data.user.mail, 'exludeAlreadyConnected': 'true' } })
            .subscribe((dataUsers: any) => {
                if (dataUsers.length > 0) {
                    this.externalUser = dataUsers[0];
                    this.externalUser.inMaarchParapheur = true;
                    this.http.get('../rest/maarchParapheur/user/' + this.externalUser.id + '/picture')
                        .subscribe((data: any) => {
                            this.externalUser.picture = data.picture;
                        }, (err) => {
                            this.notify.handleErrors(err);
                        });
                } else {
                    this.externalUser.inMaarchParapheur = false;
                    this.externalUser = this.data.user;
                    this.externalUser.login = this.data.user.user_id;
                    this.externalUser.email = this.data.user.mail;
                }
            }, (err: any) => {
                this.notify.handleErrors(err);
            });

    }

    selectUser(user: any) {
        this.externalUser = user;
        this.externalUser.inMaarchParapheur = true;
        this.http.get('../rest/maarchParapheur/user/' + this.externalUser.id + '/picture')
            .subscribe((data: any) => {
                this.externalUser.picture = data.picture;
            }, (err) => {
                this.notify.handleErrors(err);
            });
    }

    unlinkMaarchParapheurAccount() {
        this.externalUser.inMaarchParapheur = false;
        this.externalUser = this.data.user;
        this.externalUser.login = this.data.user.user_id;
        this.externalUser.email = this.data.user.mail;
    }
}
