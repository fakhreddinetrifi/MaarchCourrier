import { HttpClient } from '@angular/common/http';
import { Component, Inject, OnInit } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { TranslateService } from '@ngx-translate/core';
import { FunctionsService } from '@service/functions.service';
import { NotificationService } from '@service/notification/notification.service';
import { error } from 'jquery';
import { of } from 'rxjs';
import { catchError, finalize, tap } from 'rxjs/operators';

@Component({
    selector: 'app-send-to-record-management',
    templateUrl: 'send-to-record-management.component.html',
    styleUrls: ['send-to-record-management.component.scss']
})
export class SendToRecordManagementComponent implements OnInit {

    loading: boolean = false;
    checking: boolean = false;
    resourcesErrors: any[] = [];
    recipientArchiveEntities = [];
    archivalAgreements = [];

    descriptionLevels = [
        {
            id: 'Class',
            label: 'Classe'
        },
        {
            id: 'Collection',
            label: 'Collection'
        },
        {
            id: 'File',
            label: 'Dossier'
        },
        {
            id: 'Fonds',
            label: 'Fonds'
        },
        {
            id: 'Item',
            label: 'Pièce'
        },
        {
            id: 'RecordGrp',
            label: 'Groupe de documents'
        },
        {
            id: 'Series',
            label: 'Série organique'
        },
        {
            id: 'Subfonds',
            label: 'Sous-fonds'
        },
        {
            id: 'SubGrp',
            label: 'Sous-groupe de documents'
        },
        {
            id: 'Subseries',
            label: 'Sous-série organique'
        }
    ];

    actionFormGroup: FormGroup;
    archives: any[] = [];
    folders: any = [];
    linkedResources: any = [];

    constructor(
        public translate: TranslateService,
        public http: HttpClient,
        private notify: NotificationService,
        public dialogRef: MatDialogRef<SendToRecordManagementComponent>,
        private _formBuilder: FormBuilder,
        @Inject(MAT_DIALOG_DATA) public data: any,
        public functions: FunctionsService
    ) {
        this.actionFormGroup = this._formBuilder.group({
            folder :  [''],
            packageName: ['', Validators.required],
            slipId: [{value: '', disabled: true}, Validators.required],
            slipDate: [new Date(), Validators.required],
            archivalAgreement: [{value: '', disabled: false}, Validators.required],
            entityArchiveRecipient: [{value: '', disabled: false}, Validators.required],
            entityLabelTransferEntity: [{value: '', disabled: true}, Validators.required],
            producerTransferEntity: [{value: '', disabled: true}, Validators.required],
            senderArchiveEntity: [{value: '', disabled: true}, Validators.required],
            archiveId: [{value: '', disabled: true}, Validators.required],
            archiveDescriptionLevel: [{value: 'File', disabled: false}, Validators.required],
            doctype: [{value: '', disabled: true}, Validators.required],
            entityRetentionRule: [{value: '', disabled: true}, Validators.required],
            doctypeRetentionFinalDisposition: [{value: '', disabled: true}, Validators.required]
        });
    }

    ngOnInit(): void {
        if (this.data.resIds.length === 1) {
            this.getData();
        } else {
            this.resourcesErrors.push({lang : 'onlySingleResourceAllowed'});
        }
    }

    getData() {
        this.checking = true;
        this.http.post(`../rest/resourcesList/users/${this.data.userId}/groups/${this.data.groupId}/baskets/${this.data.basketId}/actions/${this.data.action.id}/checkSendToRecordManagement`, { resources: this.data.resIds }).pipe(
            tap((data: any) => {
                this.archives = data.archiveUnits;
                this.archives.forEach((element: any) => {
                    element.type = this.translate.instant('lang.' + element.type);
                });

                this.linkedResources = data.additionalData.linkedResources;
                this.folders = data.additionalData.folders;

                this.recipientArchiveEntities = data.recipientArchiveEntities;
                this.archivalAgreements = data.archivalAgreements;
                this.actionFormGroup = this._formBuilder.group({
                    folder : [this.folders.length > 0 ? this.folders[0].id : null],
                    packageName: ['', Validators.required],
                    slipId: [{value: data.data.slipInfo.slipId, disabled: true}, Validators.required],
                    slipDate: [new Date(), Validators.required],
                    archivalAgreement: [{value: '', disabled: false}, Validators.required],
                    entityArchiveRecipient: [{value: '', disabled: false}, Validators.required],
                    entityLabelTransferEntity: [{value: data.data.entity.label, disabled: true}, Validators.required],
                    producerTransferEntity: [{value: data.data.entity.producerService, disabled: true}, Validators.required],
                    senderArchiveEntity: [{value: data.data.entity.senderArchiveEntity, disabled: true}, Validators.required],
                    archiveId: [{value: data.data.slipInfo.archiveId, disabled: true}, Validators.required],
                    archiveDescriptionLevel: [{value: 'File', disabled: false}, Validators.required],
                    doctype: [{value: data.data.doctype.label, disabled: true}, Validators.required],
                    entityRetentionRule: [{value: data.data.doctype.retentionRule, disabled: true}, Validators.required],
                    doctypeRetentionFinalDisposition: [{value: data.data.doctype.retentionFinalDisposition, disabled: true}, Validators.required],
                });
            }),
            finalize(() => this.checking = false),
            catchError((err: any) => {
                if (!this.functions.empty(err.error.lang)) {
                    this.resourcesErrors.push(this.translate.instant('lang.' + err.error.lang));
                } else {
                    this.resourcesErrors.push(err.error.errors);
                }
                return of(false);
            })
        ).subscribe();
    }

    onSubmit(mode: string) {
        this.loading = true;
        if (this.data.resIds.length > 0) {
            this.executeAction(mode);
        }
    }

    executeAction(mode: string) {
        const realResSelected: number[] = this.data.resIds;

        this.http.put(this.data.processActionRoute, { resources: realResSelected, data: this.formatData(mode) }).pipe(
            tap((data: any) => {
                if (mode === 'download' && !this.functions.empty(data.data.encodedFile)) {
                    const downloadLink = document.createElement('a');
                    downloadLink.href = `data:application/zip;base64,${data.data.encodedFile}`;
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
                    downloadLink.setAttribute('download', 'seda_package_' + today + '.zip');
                    document.body.appendChild(downloadLink);
                    downloadLink.click();
                    this.dialogRef.close('success');
                } else if (!data) {
                    this.dialogRef.close('success');
                }
                if (data && data.errors != null) {
                    this.notify.error(data.errors);
                }
            }),
            finalize(() => this.loading = false),
            catchError((err: any) => {
                this.notify.handleErrors(err);
                return of(false);
            })
        ).subscribe();
    }

    formatData(mode: string) {
        const dataToSend = {};
        Object.keys(this.actionFormGroup.controls).forEach(element => {
            dataToSend[element] = this.actionFormGroup.controls[element].value;
        });
        dataToSend['archives'] = this.archives.map((archive: any) => {
            return {
                id: archive.id,
                descriptionLevel : archive.descriptionLevel
            };
        });
        dataToSend['actionMode'] = mode;
        return dataToSend;
    }

    archivalAgreementSelected(ev: any) {
        const archivalAgreement = this.archivalAgreements.filter((element: any) => element.id === ev.value);
        this.actionFormGroup.patchValue({entityArchiveRecipient: archivalAgreement[0].archiveEntityRegNumber});
    }

    entityArchiveRecipientSelected(ev: any) {
        if (!this.functions.empty(this.actionFormGroup.get('archivalAgreement').value) && !this.functions.empty(ev.value)) {
            const archivalAgreement = this.archivalAgreements.filter((element: any) => element.id === this.actionFormGroup.get('archivalAgreement').value && element.archiveEntityRegNumber === ev.value);
            if (archivalAgreement.length === 0) {
                this.actionFormGroup.patchValue({archivalAgreement: null});
            }
        }
    }
}
