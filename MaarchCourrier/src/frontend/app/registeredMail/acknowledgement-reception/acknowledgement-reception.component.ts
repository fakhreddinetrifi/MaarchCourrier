import { Component, ElementRef, OnInit, ViewChild } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { FormBuilder, FormGroup, ValidatorFn, Validators } from '@angular/forms';
import {catchError, debounceTime, tap} from 'rxjs/operators';
import { of } from 'rxjs';
import { NotificationService } from '@service/notification/notification.service';
import { HeaderService } from '@service/header.service';
import { FunctionsService } from '@service/functions.service';
import { AppService } from '@service/app.service';
import { TranslateService } from '@ngx-translate/core';
import { MatTableDataSource } from '@angular/material/table';

@Component({
    selector: 'app-acknowledgement-reception',
    templateUrl: 'acknowledgement-reception.component.html'
})

export class AcknowledgementReceptionComponent implements OnInit {

    loading: boolean = false;

    today: Date = new Date();

    type: any;
    number: any;
    receivedDate: any = this.today;
    reason: any;

    reasonOther: any;

    adminFormGroup: FormGroup;

    dataSource: MatTableDataSource<any>;
    displayedColumns = ['type', 'number', 'receivedDate', 'returnReason'];

    returnReasons = [
        this.translate.instant('lang.returnReasonCannotAccess'),
        this.translate.instant('lang.returnReasonNotClaimed'),
        this.translate.instant('lang.returnReasonRejected'),
        this.translate.instant('lang.returnReasonUnknown')
    ];

    @ViewChild('numberInput', { static: false }) numberInput: ElementRef;

    constructor(
        public http: HttpClient,
        private notify: NotificationService,
        private headerService: HeaderService,
        public functions: FunctionsService,
        public appService: AppService,
        public translate: TranslateService,
        private _formBuilder: FormBuilder
    ) {

    }

    ngOnInit() {
        this.headerService.setHeader(this.translate.instant('lang.arReception'));
        const validatorNumber: ValidatorFn[] = [Validators.pattern(/(2C|2D|RW) ([0-9]{3} [0-9]{3} [0-9]{4}) ([0-9])/), Validators.required];
        this.adminFormGroup = this._formBuilder.group({
            type: ['', Validators.required],
            number: ['', validatorNumber],
            receivedDate: [''],
            returnReason: [''],
            returnReasonOther: ['']
        });
        this.loading = false;
        this.dataSource = new MatTableDataSource([]);
        this.returnReasons.sort();

        this.adminFormGroup.controls['number'].valueChanges.pipe(
            debounceTime(500),
            tap(() => this.receiveAcknowledgement())
        ).subscribe();
    }

    receiveAcknowledgement() {
        const data = {
            type: this.type,
            number: this.number,
            receivedDate: this.functions.formatDateObjectToDateString(this.receivedDate),
            returnReason: this.reason
        };

        if (this.functions.empty(this.number)) {
            return;
        }
        if (!this.adminFormGroup.get('number').valid) {
            return;
        }
        if (this.type === 'notDistributed') {
            if (!this.adminFormGroup.get('receivedDate').valid || !this.adminFormGroup.get('returnReason').valid) {
                this.notify.error(this.translate.instant('lang.fieldsNotValid'));
                return;
            }
            if (this.reason === this.translate.instant('lang.others') && this.functions.empty(this.reasonOther)) {
                this.notify.error(this.translate.instant('lang.fieldsNotValid'));
                return;
            } else if (this.reason === this.translate.instant('lang.others') && !this.functions.empty(this.reasonOther)) {
                data.returnReason = this.reasonOther;
            }
        }

        this.http.put('../rest/registeredMails/acknowledgement', data).pipe(
            tap(() => {
                this.notify.success(this.translate.instant('lang.arReceived'));

                const receivedList = this.dataSource.data;
                receivedList.unshift(data);
                this.dataSource.data = receivedList;

                this.number = '';
                this.receivedDate = this.today;
                this.reason = '';
                this.reasonOther = '';

                this.focusRegisteredMailNumber();
            }),
            catchError((err) => {
                this.notify.handleSoftErrors(err);
                return of(false);
            })
        ).subscribe();
    }

    focusRegisteredMailNumber() {
        setTimeout(() => {
                this.numberInput.nativeElement.focus();
        }, 0);
    }

    changeType(type: any) {
        if (type === 'distributed') {
            this.adminFormGroup.get('receivedDate').disable();
        } else {
            this.adminFormGroup.get('receivedDate').enable();
        }
    }
}
