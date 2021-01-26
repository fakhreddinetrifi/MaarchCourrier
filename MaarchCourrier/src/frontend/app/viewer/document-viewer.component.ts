import {Component, OnInit, Input, ViewChild, EventEmitter, Output, OnDestroy} from '@angular/core';
import { HttpClient, HttpEventType } from '@angular/common/http';
import { TranslateService } from '@ngx-translate/core';
import { NotificationService } from '@service/notification/notification.service';
import { HeaderService } from '@service/header.service';
import { AppService } from '@service/app.service';
import { tap, catchError, filter, map, exhaustMap, take, finalize } from 'rxjs/operators';
import { ConfirmComponent } from '../../plugins/modal/confirm.component';
import { MatDialogRef, MatDialog } from '@angular/material/dialog';
import { AlertComponent } from '../../plugins/modal/alert.component';
import { SortPipe } from '../../plugins/sorting.pipe';
import { PluginSelectSearchComponent } from '../../plugins/select-search/select-search.component';
import { FormControl } from '@angular/forms';
import { EcplOnlyofficeViewerComponent } from '../../plugins/onlyoffice-api-js/onlyoffice-viewer.component';
import { FunctionsService } from '@service/functions.service';
import { DocumentViewerModalComponent } from './modal/document-viewer-modal.component';
import { PrivilegeService } from '@service/privileges.service';
import { VisaWorkflowModalComponent } from '../visa/modal/visa-workflow-modal.component';
import { of } from 'rxjs';
import { CollaboraOnlineViewerComponent } from '../../plugins/collabora-online/collabora-online-viewer.component';
import { AuthService } from '@service/auth.service';
import { LocalStorageService } from '@service/local-storage.service';

@Component({
    selector: 'app-document-viewer',
    templateUrl: 'document-viewer.component.html',
    styleUrls: [
        'document-viewer.component.scss',
        '../indexation/indexing-form/indexing-form.component.scss',
    ],
    providers: [SortPipe]
})

export class DocumentViewerComponent implements OnInit, OnDestroy {

    /**
     * document name stored in server (in tmp folder)
     */
    @Input() tmpFilename: string;

    /**
     * base64 of document  (@format is required!)
     */
    @Input() base64: any = null;
    @Input() format: string = null;

    /**
     * Target of resource (document or attachment)
     */
    @Input() mode: 'mainDocument' | 'attachment' = 'mainDocument';

    /**
     * Resource of document or attachment (based on @mode)
     */
    @Input() resId: number = null;


    /**
     * Resource of document link to attachment (@mode = 'attachment' required!)
     */
    @Input() resIdMaster: number = null;

    /**
     * Can manage document ? (create, delete, update)
     */
    @Input() editMode: boolean = false;

    /**
     * Hide tool document viewer
     */
    @Input() hideTools: boolean = false;

    /**
     * Title of new tab when open document in external tab
     */
    @Input() title: string = '';


    /**
     * To load specific attachment type in template list (to create document)
     */
    @Input() attachType: string = null;

    /**
     * Event emitter
     */
    @Output() triggerEvent = new EventEmitter<string>();

    

    loading: boolean = true;
    noConvertedFound: boolean = false;

    noFile: boolean = false;

    file: any = {
        name: '',
        type: '',
        contentMode: 'base64',
        content: null,
        src: null
    };

    allowedExtensions: any[] = [];
    maxFileSize: number = 0;
    maxFileSizeLabel: string = '';

    percentInProgress: number = 0;

    intervalLockFile: any;
    editInProgress: boolean = false;


    listTemplates: any[] = [];
    externalId: any = {};

    templateListForm = new FormControl();

    resourceDatas: any;

    loadingInfo: any = {
        mode: 'indeterminate',
        percent: 0,
        message: '',
    };

    dialogRef: MatDialogRef<any>;
    editor: any = {
        mode: '',
        async: true,
        options: {
            docUrl: null,
            dataToMerge: null
        }
    };

    isDocModified: boolean = false;

    @ViewChild('templateList', { static: true }) templateList: PluginSelectSearchComponent;
    @ViewChild('onlyofficeViewer', { static: false }) onlyofficeViewer: EcplOnlyofficeViewerComponent;
    @ViewChild('collaboraOnlineViewer', { static: false }) collaboraOnlineViewer: CollaboraOnlineViewerComponent;

    docToUploadValue: any;

    constructor(
        public translate: TranslateService,
        public http: HttpClient,
        private notify: NotificationService,
        public headerService: HeaderService,
        public appService: AppService,
        private dialog: MatDialog,
        private sortPipe: SortPipe,
        public functions: FunctionsService,
        public privilegeService: PrivilegeService,
        private authService: AuthService,
        private localStorage: LocalStorageService
    ) {
        (<any>window).pdfWorkerSrc = '../node_modules/pdfjs-dist/build/pdf.worker.min.js';
    }

    ngOnInit() {
        this.setEditor();

        this.http.get('../rest/indexing/fileInformations').pipe(
            tap((data: any) => {
                this.allowedExtensions = data.informations.allowedFiles.map((ext: any) => {
                    return {
                        extension: '.' + ext.extension.toLowerCase(),
                        mimeType: ext.mimeType,
                        canConvert: ext.canConvert
                    };
                });
                this.allowedExtensions = this.sortPipe.transform(this.allowedExtensions, 'extension');

                this.maxFileSize = data.informations.maximumSize;
                this.maxFileSizeLabel = data.informations.maximumSizeLabel;

                if (this.resId !== null) {
                    this.loadRessource(this.resId, this.mode);
                    if (this.editMode) {
                        if (this.attachType !== null && this.mode === 'attachment') {
                            this.loadTemplatesByResId(this.resIdMaster, this.attachType);
                        } else {
                            this.loadTemplates();
                        }
                    }
                } else {
                    this.loadTemplates();
                    this.loading = false;
                }
            }),
            catchError((err: any) => {
                this.notify.handleErrors(err);
                return of(false);
            })
        ).subscribe();

        if (!this.functions.empty(this.base64)) {
            this.loadFileFromBase64();
        } else if (this.tmpFilename !== '' && this.tmpFilename !== undefined) {
            this.http.get('../rest/convertedFile/' + this.tmpFilename).pipe(
                tap((data: any) => {
                    this.file = {
                        name: this.tmpFilename,
                        format: 'pdf',
                        type: 'application/pdf',
                        contentMode: 'base64',
                        content: this.getBase64Document(this.base64ToArrayBuffer(data.encodedResource)),
                        src: this.base64ToArrayBuffer(data.encodedResource)
                    };
                    this.noConvertedFound = false;
                    this.loading = false;
                }),
                catchError((err: any) => {
                    this.notify.handleErrors(err);
                    return of(false);
                })
            ).subscribe();
        }
    }

    ngOnDestroy() {
        if (this.intervalLockFile) {
            this.cancelTemplateEdition();
        }
    }

    loadFileFromBase64() {
        this.loading = true;
        this.file = {
            name: 'maarch',
            format: 'pdf',
            type: 'application/pdf',
            contentMode: 'base64',
            content: this.base64,
            src: this.base64ToArrayBuffer(this.base64)
        };
        this.loading = false;
    }

    loadTmpFile(filenameOnTmp: string) {
        return new Promise((resolve, reject) => {
            this.loading = true;
            this.loadingInfo.mode = 'determinate';

            this.requestWithLoader(`../rest/convertedFile/${filenameOnTmp}?convert=true`).subscribe(
                (data: any) => {
                    if (data.encodedResource) {
                        this.file = {
                            name: filenameOnTmp,
                            format: data.extension,
                            type: data.type,
                            contentMode: 'base64',
                            content: data.encodedResource,
                            src: data.encodedConvertedResource !== undefined ? this.base64ToArrayBuffer(data.encodedConvertedResource) : null
                        };
                        this.editMode = true;
                        this.triggerEvent.emit();
                        if (data.encodedConvertedResource !== undefined) {
                            this.noConvertedFound = false;
                        } else {
                            this.noConvertedFound = true;
                            this.notify.error(data.convertedResourceErrors);
                        }
                        this.loading = false;
                        resolve(true);
                    }
                },
                (err: any) => {
                    this.noConvertedFound = true;
                    this.notify.handleErrors(err);
                    this.loading = false;
                    resolve(true);
                    return of(false);
                }
            );
        });
    }

    uploadTrigger(fileInput: any) {
        if (fileInput.target.files && fileInput.target.files[0] && this.isExtensionAllowed(fileInput.target.files[0])) {
            this.initUpload();

            const reader = new FileReader();
            this.file.name = fileInput.target.files[0].name;
            this.file.type = fileInput.target.files[0].type;
            this.file.format = this.file.name.split('.').pop();

            reader.readAsArrayBuffer(fileInput.target.files[0]);

            reader.onload = (value: any) => {
                this.file.content = this.getBase64Document(value.target.result);
                this.triggerEvent.emit();
                if (this.file.type !== 'application/pdf') {
                    this.convertDocument(this.file);
                } else {
                    this.file.src = value.target.result;
                    this.loading = false;
                }
            };
        } else {
            this.loading = false;
        }
    }

    initUpload() {
        this.loading = true;
        this.file = {
            name: '',
            type: '',
            contentMode: 'base64',
            content: null,
            src: null
        };
        this.noConvertedFound = false;
        this.loadingInfo.message = this.translate.instant('lang.loadingFile') + '...';
        this.loadingInfo.mode = 'indeterminate';
    }

    getBase64Document(buffer: ArrayBuffer) {
        const TYPED_ARRAY = new Uint8Array(buffer);
        const STRING_CHAR = TYPED_ARRAY.reduce((data, byte) => {
            return data + String.fromCharCode(byte);
        }, '');

        return btoa(STRING_CHAR);
    }

    base64ToArrayBuffer(base64: string) {
        const binary_string = window.atob(base64);
        const len = binary_string.length;
        const bytes = new Uint8Array(len);
        for (let i = 0; i < len; i++) {
            bytes[i] = binary_string.charCodeAt(i);
        }
        return bytes.buffer;
    }

    b64toBlob(b64Data: any, contentType = '', sliceSize = 512) {
        const byteCharacters = atob(b64Data);
        const byteArrays = [];

        for (let offset = 0; offset < byteCharacters.length; offset += sliceSize) {
            const slice = byteCharacters.slice(offset, offset + sliceSize);

            const byteNumbers = new Array(slice.length);
            for (let i = 0; i < slice.length; i++) {
                byteNumbers[i] = slice.charCodeAt(i);
            }

            const byteArray = new Uint8Array(byteNumbers);
            byteArrays.push(byteArray);
        }

        const blob = new Blob(byteArrays, { type: contentType });
        return blob;
    }

    convertDocument(file: any) {
        if (this.canBeConverted(file)) {
            const data = { name: file.name, base64: file.content };
            this.upload(data).subscribe(
                (res: any) => {
                    if (res.encodedResource) {
                        this.file.base64src = res.encodedResource;
                        this.file.src = this.base64ToArrayBuffer(res.encodedResource);
                        this.loading = false;
                    }
                },
                (err: any) => {
                    this.noConvertedFound = true;
                    this.notify.handleErrors(err);
                    this.loading = false;
                    return of(false);
                }
            );
        } else {
            this.noConvertedFound = true;
            this.loading = false;
        }

    }

    upload(data: any) {
        const uploadURL = `../rest/convertedFile`;

        return this.http.post<any>(uploadURL, data, {
            reportProgress: true,
            observe: 'events'
        }).pipe(map((event) => {

            switch (event.type) {
                case HttpEventType.DownloadProgress:

                    const downloadProgress = Math.round(100 * event.loaded / event.total);
                    this.loadingInfo.percent = downloadProgress;
                    this.loadingInfo.mode = 'determinate';
                    this.loadingInfo.message = `3/3 ${this.translate.instant('lang.downloadConvertedFile')}...`;

                    return { status: 'progress', message: downloadProgress };

                case HttpEventType.UploadProgress:
                    const progress = Math.round(100 * event.loaded / event.total);
                    this.loadingInfo.percent = progress;

                    if (progress === 100) {
                        this.loadingInfo.mode = 'indeterminate';
                        this.loadingInfo.message = `2/3 ${this.translate.instant('lang.convertingFile')}...`;
                    } else {
                        this.loadingInfo.mode = 'determinate';
                        this.loadingInfo.message = `1/3 ${this.translate.instant('lang.loadingFile')}...`;
                    }
                    return { status: 'progress', message: progress };

                case HttpEventType.Response:
                    return event.body;
                default:
                    return `Unhandled event: ${event.type}`;
            }
        })
        );
    }

    requestWithLoader(url: string) {
        this.loadingInfo.percent = 0;

        return this.http.get<any>(url, {
            reportProgress: true,
            observe: 'events'
        }).pipe(map((event) => {
            switch (event.type) {
                case HttpEventType.DownloadProgress:

                    const downloadProgress = Math.round(100 * event.loaded / event.total);
                    this.loadingInfo.percent = downloadProgress;
                    this.loadingInfo.mode = 'determinate';
                    this.loadingInfo.message = ``;

                    return { status: 'progressDownload', message: downloadProgress };

                case HttpEventType.Response:
                    return event.body;
                default:
                    return `Unhandled event: ${event.type}`;
            }
        })
        );
    }

    onError(error: any) {
        console.log(error);
    }

    cleanFile() {
        this.dialogRef = this.dialog.open(ConfirmComponent, { panelClass: 'maarch-modal', autoFocus: false, disableClose: true, data: { title: this.translate.instant('lang.delete'), msg: this.translate.instant('lang.confirmAction') } });

        this.dialogRef.afterClosed().pipe(
            filter((data: string) => data === 'ok'),
            tap(() => {
                this.templateListForm.reset();
                this.file = {
                    name: '',
                    type: '',
                    content: null,
                    src: null
                };
                this.docToUploadValue = '';
                this.triggerEvent.emit('cleanFile');
            }),
            catchError((err: any) => {
                this.notify.handleErrors(err);
                return of(false);
            })
        ).subscribe();

    }

    async saveDocService() {
        const data: any = await this.getFilePdf();

        this.headerService.setLoadedFile(data);
    }

    getFile() {
        if (this.editor.mode === 'onlyoffice' && this.onlyofficeViewer !== undefined) {
            return this.onlyofficeViewer.getFile();
        } else if (this.editor.mode === 'collaboraOnline' && this.collaboraOnlineViewer !== undefined) {
            return this.collaboraOnlineViewer.getFile();
        } else {
            const objFile = JSON.parse(JSON.stringify(this.file));
            objFile.content = objFile.contentMode === 'route' ? null : objFile.content;

            return of(objFile);
        }
    }

    getFilePdf() {
        return new Promise((resolve, reject) => {
            if (!this.functions.empty(this.file.src)) {
                resolve(this.getBase64Document(this.file.src));
            } else {
                this.getFile().pipe(
                    take(1),
                    tap((data: any) => {
                        if (this.editor.mode === 'collaboraOnline' && this.collaboraOnlineViewer !== undefined) {
                            this.collaboraOnlineViewer.isSaving = false;
                        }
                        return data;
                    }),
                    exhaustMap((data: any) => this.http.post(`../rest/convertedFile`, { name: `${data.name}.${data.format}`, base64: `${data.content}` })),
                    tap((data: any) => {
                        resolve(data.encodedResource);
                    })
                ).subscribe();
            }
        });
    }

    dndUploadFile(event: any) {
        const fileInput = {
            target: {
                files: [
                    event[0]
                ]
            }
        };
        this.uploadTrigger(fileInput);
    }

    canBeConverted(file: any): boolean {
        const fileExtension = '.' + file.name.toLowerCase().split('.').pop();
        if (this.allowedExtensions.filter(ext => ext.canConvert === true && ext.mimeType === file.type && ext.extension === fileExtension).length > 0) {
            return true;
        } else {
            return false;
        }
    }

    isExtensionAllowed(file: any) {
        const fileExtension = '.' + file.name.toLowerCase().split('.').pop();
        if (this.allowedExtensions.filter(ext => ext.mimeType === file.type && ext.extension === fileExtension).length === 0) {
            this.dialog.open(AlertComponent, { panelClass: 'maarch-modal', autoFocus: false, disableClose: true, data: { title: this.translate.instant('lang.notAllowedExtension') + ' !', msg: this.translate.instant('lang.file') + ' : <b>' + file.name + '</b>, ' + this.translate.instant('lang.type') + ' : <b>' + file.type + '</b><br/><br/><u>' + this.translate.instant('lang.allowedExtensions') + '</u> : <br/>' + this.allowedExtensions.map(ext => ext.extension).filter((elem: any, index: any, self: any) => index === self.indexOf(elem)).join(', ') } });
            return false;
        } else if (file.size > this.maxFileSize && this.maxFileSize > 0) {
            this.dialog.open(AlertComponent, { panelClass: 'maarch-modal', autoFocus: false, disableClose: true, data: { title: this.translate.instant('lang.maxFileSizeReached') + ' ! ', msg: this.translate.instant('lang.maxFileSize') + ' : ' + this.maxFileSizeLabel } });
            return false;
        } else {
            return true;
        }
    }

    downloadOriginalFile() {
        const downloadLink = document.createElement('a');
        if (this.file.contentMode === 'base64') {
            downloadLink.href = `data:${this.file.type};base64,${this.file.content}`;
            downloadLink.setAttribute('download', this.file.name);
            document.body.appendChild(downloadLink);
            downloadLink.click();
        } else {
            this.http.get(this.file.content).pipe(
                tap((data: any) => {
                    downloadLink.href = `data:${data.mimeType};base64,${data.encodedDocument}`;
                    downloadLink.setAttribute('download', this.file.name);
                    document.body.appendChild(downloadLink);
                    downloadLink.click();
                }),
                catchError((err: any) => {
                    this.notify.handleSoftErrors(err);
                    return of(false);
                })
            ).subscribe();
        }
    }

    openPdfInTab() {
        let src = '';
        if (this.file.contentMode === 'base64') {
            src = `data:${this.file.type};base64,${this.file.content}`;
            const newWindow = window.open();
            newWindow.document.write(`<iframe style="width: 100%;height: 100%;margin: 0;padding: 0;" src="${src}" frameborder="0" allowfullscreen></iframe>`);
            newWindow.document.title = this.title;
        } else {
            this.http.get(this.file.contentView).pipe(
                tap((data: any) => {
                    src = `data:${data.mimeType};base64,${data.encodedDocument}`;
                    const newWindow = window.open();
                    newWindow.document.write(`<iframe style="width: 100%;height: 100%;margin: 0;padding: 0;" src="${src}" frameborder="0" allowfullscreen></iframe>`);
                    newWindow.document.title = this.title;
                }),
                catchError((err: any) => {
                    this.notify.handleSoftErrors(err);
                    return of(false);
                })
            ).subscribe();
        }
    }

    async loadRessource(resId: any, target: any = 'mainDocument') {
        this.resId = resId;
        this.mode = target;
        this.loading = true;
        if (target === 'attachment') {
            this.requestWithLoader(`../rest/attachments/${resId}/content?mode=base64`).subscribe(
                (data: any) => {
                    if (data.encodedDocument) {
                        this.file.contentMode = 'route';
                        this.file.name = `${resId}.${data.originalFormat}`;
                        this.file.format = data.originalFormat;
                        this.file.creatorId = data.originalCreatorId;
                        this.file.signatoryId = data.signatoryId;
                        this.file.content = `../rest/attachments/${resId}/originalContent?mode=base64`;
                        this.file.contentView = `../rest/attachments/${resId}/content?mode=base64`;
                        this.file.src = this.base64ToArrayBuffer(data.encodedDocument);
                        this.loading = false;
                        this.noFile = false;
                    }
                },
                (err: any) => {
                    if (err.error.errors === 'Document has no file') {
                        this.noFile = true;
                    } else if (err.error.errors === 'Converted Document not found' || err.error.errors === 'Document can not be converted') {
                        this.file.contentMode = 'route';
                        this.file.content = `../rest/attachments/${resId}/originalContent?mode=base64`;
                        this.noConvertedFound = true;
                    } else {
                        this.notify.error(err.error.errors);
                        this.noFile = true;
                    }
                    this.loading = false;
                    return of(false);
                }
            );
        } else {
            await this.loadMainDocumentSubInformations();

            if (this.file.subinfos.mainDocVersions.length === 0) {
                this.noFile = true;
                this.loading = false;
            } else if (!this.file.subinfos.canConvert) {
                this.file.contentMode = 'route';
                this.file.content = `../rest/resources/${resId}/originalContent?mode=base64`;
                this.noConvertedFound = true;
                this.loading = false;
            } else {
                this.requestWithLoader(`../rest/resources/${resId}/content?mode=base64`).subscribe(
                    (data: any) => {
                        if (data.encodedDocument) {
                            this.file.contentMode = 'route';
                            this.file.name = `${resId}.${data.originalFormat}`;
                            this.file.format = data.originalFormat;
                            this.file.signatoryId = data.signatoryId;
                            this.file.content = `../rest/resources/${resId}/originalContent?mode=base64`;
                            this.file.contentView = `../rest/resources/${resId}/content?mode=base64`;
                            this.file.src = this.base64ToArrayBuffer(data.encodedDocument);
                            this.loading = false;
                            this.noFile = false;
                        }
                    },
                    (err: any) => {
                        this.notify.error(err.error.errors);
                        this.noFile = true;
                        this.loading = false;
                        return of(false);
                    }
                );
                this.http.get(`../rest/resources/${this.resId}/fields/externalId`).pipe(
                    tap((data: any) => {
                        this.externalId = data.field;
                    }),
                    catchError((err: any) => {
                        this.notify.handleSoftErrors(err);
                        return of(false);
                    })
                ).subscribe();
            }
        }
    }

    loadMainDocumentSubInformations() {
        return new Promise((resolve, reject) => {
            this.http.get(`../rest/resources/${this.resId}/versionsInformations`).pipe(
                tap((data: any) => {
                    const mainDocVersions = data.DOC;
                    let mainDocPDFVersions = false;
                    let signedDocVersions = false;
                    let commentedDocVersions = false;
                    if (data.DOC[data.DOC.length - 1] !== undefined) {
                        signedDocVersions = data.SIGN.indexOf(data.DOC[data.DOC.length - 1]) > -1 ? true : false;
                        commentedDocVersions = data.NOTE.indexOf(data.DOC[data.DOC.length - 1]) > -1 ? true : false;
                        mainDocPDFVersions = data.PDF.indexOf(data.DOC[data.DOC.length - 1]) > -1 ? true : false;
                    }

                    this.file.subinfos = {
                        mainDocVersions: mainDocVersions,
                        signedDocVersions: signedDocVersions,
                        commentedDocVersions: commentedDocVersions,
                        mainDocPDFVersions: mainDocPDFVersions
                    };
                }),
                exhaustMap(() => this.http.get(`../rest/resources/${this.resId}/fileInformation`)),
                tap((data: any) => {
                    this.file.subinfos.canConvert = data.information.canConvert;
                    resolve(true);
                }),
                catchError((err: any) => {
                    this.notify.handleSoftErrors(err);
                    return of(false);
                })
            ).subscribe();
        });
    }

    editTemplate(templateId: number) {
        if (this.localStorage.get(`modal_confirmEditTemplate_${this.headerService.user.id}`) !== null) {
            this.launchEditTemplate(templateId);
        } else {
            this.confirmEditTemplate(templateId);
        }
    }

    confirmEditTemplate(templateId: number) {
        let confirmMsg = '';
        if (this.mode === 'attachment') {
            confirmMsg = this.translate.instant('lang.editionAttachmentConfirmFirst') + '<br><br>' + this.translate.instant('lang.editionAttachmentConfirmThird');
        } else {
            confirmMsg = this.translate.instant('lang.editionAttachmentConfirmFirst') + '<br><br>' + this.translate.instant('lang.editionAttachmentConfirmSecond');
        }
        this.dialogRef = this.dialog.open(ConfirmComponent, { panelClass: 'maarch-modal', autoFocus: false, disableClose: true, data: { idModal: 'confirmEditTemplate', title: this.translate.instant('lang.templateEdition'), msg: confirmMsg } });

        this.dialogRef.afterClosed().pipe(
            tap((data: string) => {
                if (data !== 'ok') {
                    this.templateListForm.reset();
                }
            }),
            filter((data: string) => data === 'ok'),
            tap(() => {
                this.launchEditTemplate(templateId);
            }),
            catchError((err: any) => {
                this.notify.handleErrors(err);
                return of(false);
            })
        ).subscribe();
    }

    launchEditTemplate(templateId: number) {
        this.triggerEvent.emit();
        const template = this.listTemplates.filter(templateItem => templateItem.id === templateId)[0];

        this.file.format = template.extension;

        if (this.editor.mode === 'onlyoffice') {

            this.editor.async = false;
            this.editor.options = {
                objectType: 'attachmentCreation',
                objectId: template.id,
                docUrl: `rest/onlyOffice/mergedFile`,
                dataToMerge: this.resourceDatas
            };
            this.editInProgress = true;

        } else if (this.editor.mode === 'collaboraOnline') {
            this.editor.async = false;
            this.editInProgress = true;
            this.editor.options = {
                objectType: this.mode === 'attachment' ? 'attachmentCreation' : 'resourceCreation',
                objectId: template.id,
                dataToMerge: this.resourceDatas
            };
        } else {
            this.editor.async = true;
            this.editor.options = {
                objectType: 'attachmentCreation',
                objectId: template.id,
                cookie: document.cookie,
                authToken : this.authService.getToken(),
                data: this.resourceDatas,
            };
            this.editInProgress = true;

            this.http.post('../rest/jnlp', this.editor.options).pipe(
                tap((data: any) => {
                    window.location.href = '../rest/jnlp/' + data.generatedJnlp;
                    this.checkLockFile(data.jnlpUniqueId, template.extension);
                })
            ).subscribe();
        }
    }

    editResource() {
        if (this.mode === 'attachment') {
            this.editAttachment();
        } else {
            this.editMainDocument();
        }
    }

    editAttachment() {

        this.triggerEvent.emit('setData');

        if (this.editor.mode === 'onlyoffice') {
            this.editor.async = false;
            this.editor.options = {
                objectType: 'attachmentModification',
                objectId: this.resId,
                docUrl: `rest/onlyOffice/mergedFile`,
                dataToMerge: this.resourceDatas
            };
            this.editInProgress = true;

        } else if (this.editor.mode === 'collaboraOnline') {
            this.editor.async = false;
            this.editInProgress = true;
            this.editor.options = {
                objectType: 'attachmentModification',
                objectId: this.resId,
                dataToMerge: this.resourceDatas
            };
        } else {
            this.editor.async = true;
            this.editor.options = {
                objectType: 'attachmentModification',
                objectId: this.resId,
                cookie: document.cookie,
                authToken : this.authService.getToken(),
                data: this.resourceDatas,
            };
            this.editInProgress = true;

            this.http.post('../rest/jnlp', this.editor.options).pipe(
                tap((data: any) => {
                    window.location.href = '../rest/jnlp/' + data.generatedJnlp;
                    this.checkLockFile(data.jnlpUniqueId, this.file.format);
                })
            ).subscribe();
        }
    }

    editMainDocument() {
        if (this.editor.mode === 'onlyoffice') {
            this.editor.async = false;
            this.editor.options = {
                objectType: 'resourceModification',
                objectId: this.resId,
                docUrl: `rest/onlyOffice/mergedFile`
            };
            this.editInProgress = true;

        } else if (this.editor.mode === 'collaboraOnline') {
            this.editor.async = false;
            this.editor.options = {
                objectType: 'resourceModification',
                objectId: this.resId,
                dataToMerge: this.resourceDatas
            };
            this.editInProgress = true;
        } else {
            this.editor.async = true;
            this.editor.options = {
                objectType: 'resourceModification',
                objectId: this.resId,
                cookie: document.cookie
            };
            this.editInProgress = true;

            this.http.post('../rest/jnlp', this.editor.options).pipe(
                tap((data: any) => {
                    window.location.href = '../rest/jnlp/' + data.generatedJnlp;
                    this.checkLockFile(data.jnlpUniqueId, this.file.format);
                })
            ).subscribe();
        }
    }

    setDatas(resourceDatas: any) {
        this.resourceDatas = resourceDatas;
    }

    checkLockFile(id: string, extension: string) {
        this.intervalLockFile = setInterval(() => {
            this.http.get('../rest/jnlp/lock/' + id)
                .subscribe(async (data: any) => {
                    if (!data.lockFileFound) {
                        this.editInProgress = false;
                        clearInterval(this.intervalLockFile);
                        await this.loadTmpFile(`${data.fileTrunk}.${extension}`);
                        if (this.mode === 'mainDocument' && this.resId !== null) {
                            this.saveMainDocument();
                        }
                    }
                });
        }, 1000);
    }

    cancelTemplateEdition() {
        clearInterval(this.intervalLockFile);
        this.editInProgress = false;
    }

    isEditingTemplate() {
        if (this.editor.mode === 'onlyoffice') {
            return this.onlyofficeViewer !== undefined;
        } else if (this.editor.mode === 'collaboraOnline') {
            return this.collaboraOnlineViewer !== undefined;
        } else {
            return this.editInProgress;
        }
    }

    loadTemplatesByResId(resId: number, attachType: string) {
        const arrValues: any[] = [];
        let arrTypes: any = [];
        this.listTemplates = [];
        this.http.get('../rest/attachmentsTypes').pipe(
            tap((data: any) => {

                Object.keys(data.attachmentsTypes).forEach(templateType => {
                    arrTypes.push({
                        id: templateType,
                        label: data.attachmentsTypes[templateType].label
                    });
                });
                arrTypes = this.sortPipe.transform(arrTypes, 'label');
                arrTypes.push({
                    id: 'all',
                    label: this.translate.instant('lang.others')
                });

            }),
            exhaustMap(() => this.http.get(`../rest/resources/${resId}/templates?attachmentType=${attachType},all`)),
            tap((data: any) => {
                this.listTemplates = data.templates;

                arrTypes = arrTypes.filter((type: any) => data.templates.map((template: any) => template.attachmentType).indexOf(type.id) > -1);

                arrTypes.forEach((arrType: any) => {
                    arrValues.push({
                        id: arrType.id,
                        label: arrType.label,
                        title: arrType.label,
                        disabled: true,
                        isTitle: true,
                        color: '#135f7f'
                    });
                    data.templates.filter((template: any) => template.attachmentType === arrType.id).forEach((template: any) => {
                        arrValues.push({
                            id: template.id,
                            label: '&nbsp;&nbsp;&nbsp;&nbsp;' + template.label,
                            title: template.exists ? template.label : this.translate.instant('lang.fileDoesNotExists'),
                            extension: template.extension,
                            disabled: !template.exists,
                        });
                    });
                });

                this.listTemplates = arrValues;
            })

        ).subscribe();
    }

    loadTemplates() {
        if (this.listTemplates.length === 0) {
            const arrValues: any[] = [];
            if (this.mode === 'mainDocument') {
                this.http.get('../rest/currentUser/templates?target=indexingFile').pipe(
                    tap((data: any) => {
                        this.listTemplates = data.templates;
                        arrValues.push({
                            id: 'all',
                            label: this.translate.instant('lang.indexation'),
                            title: this.translate.instant('lang.indexation'),
                            disabled: true,
                            isTitle: true,
                            color: '#135f7f'
                        });
                        data.templates.forEach((template: any) => {
                            arrValues.push({
                                id: template.id,
                                label: '&nbsp;&nbsp;&nbsp;&nbsp;' + template.label,
                                title: template.exists ? template.label : this.translate.instant('lang.fileDoesNotExists'),
                                extension: template.extension,
                                disabled: !template.exists,
                            });
                        });
                        this.listTemplates = arrValues;
                    })

                ).subscribe();
            } else {
                let arrTypes: any = [];
                this.http.get('../rest/attachmentsTypes').pipe(
                    tap((data: any) => {
                        arrTypes.push({
                            id: 'all',
                            label: this.translate.instant('lang.others')
                        });
                        Object.keys(data.attachmentsTypes).forEach(templateType => {
                            arrTypes.push({
                                id: templateType,
                                label: data.attachmentsTypes[templateType].label
                            });
                            arrTypes = this.sortPipe.transform(arrTypes, 'label');
                        });
                    }),
                    exhaustMap(() => {
                        return this.http.get('../rest/currentUser/templates?target=attachments&type=office');
                    }),
                    tap((data: any) => {
                        this.listTemplates = data.templates;

                        arrTypes = arrTypes.filter((type: any) => data.templates.map((template: any) => template.attachmentType).indexOf(type.id) > -1);

                        arrTypes.forEach((arrType: any) => {
                            arrValues.push({
                                id: arrType.id,
                                label: arrType.label,
                                title: arrType.label,
                                disabled: true,
                                isTitle: true,
                                color: '#135f7f'
                            });
                            data.templates.filter((template: any) => template.attachmentType === arrType.id).forEach((template: any) => {
                                arrValues.push({
                                    id: template.id,
                                    label: '&nbsp;&nbsp;&nbsp;&nbsp;' + template.label,
                                    title: template.exists ? template.label : this.translate.instant('lang.fileDoesNotExists'),
                                    extension: template.extension,
                                    disabled: !template.exists,
                                });
                            });
                        });

                        this.listTemplates = arrValues;
                    })

                ).subscribe();
            }
        }
    }

    closeEditor() {
        this.templateListForm.reset();
        this.editInProgress = false;
        this.isDocModified = false;
    }

    setEditor() {
        if (this.headerService.user.preferences.documentEdition === 'java') {
            this.editor.mode = 'java';
            this.editor.async = true;
        } else if (this.headerService.user.preferences.documentEdition === 'onlyoffice') {
            this.editor.mode = 'onlyoffice';
            this.editor.async = false;
        } else if (this.headerService.user.preferences.documentEdition === 'collaboraonline') {
            this.editor.mode = 'collaboraOnline';
            this.editor.async = false;
        }
    }

    saveMainDocument() {
        this.loading = true;
        return new Promise((resolve) => {
            this.getFile().pipe(
                map((data: any) => {
                    const formatdatas = {
                        encodedFile: data.content,
                        format: data.format,
                        resId: this.resId
                    };
                    return formatdatas;
                }),
                exhaustMap((data) => this.http.put(`../rest/resources/${this.resId}?onlyDocument=true`, data)),
                tap(() => {
                    this.closeEditor();
                    this.loadRessource(this.resId);
                    resolve(true);
                }),
                finalize(() => this.loading = false),
                catchError((err: any) => {
                    this.notify.handleSoftErrors(err);
                    resolve(false);
                    return of(false);
                })
            ).subscribe();
        });
    }

    loadTmpDocument(base64Content: string, format: string) {
        return new Promise((resolve, reject) => {
            this.http.post(`../rest/convertedFile/encodedFile`, { format: format, encodedFile: base64Content }).pipe(
                tap((data: any) => {
                    this.file = {
                        name: 'maarch',
                        format: format,
                        type: 'application/pdf',
                        contentMode: 'base64',
                        content: base64Content,
                        src: this.base64ToArrayBuffer(data.encodedResource)
                    };
                }),
                // exhaustMap((data) => this.http.post(`../rest/convertedFile/encodedFile`, data.content)),
                catchError((err: any) => {
                    this.notify.handleSoftErrors(err);
                    resolve(false);
                    return of(false);
                })
            ).subscribe();
        });
    }

    saveTmpDocument() {
        return new Promise((resolve, reject) => {
            this.getFile().pipe(
                tap((data: any) => {
                    this.file = {
                        name: 'maarch',
                        format: data.format,
                        type: 'application/pdf',
                        contentMode: 'base64',
                        content: data.content,
                        src: null
                    };
                }),
                exhaustMap((data) => this.http.post(`../rest/convertedFile/encodedFile`, { format: data.format, encodedFile: data.content })),
                tap((data: any) => {
                    this.file.src = this.base64ToArrayBuffer(data.encodedResource);
                    this.closeEditor();
                    resolve(true);
                }),
                catchError((err: any) => {
                    this.notify.handleSoftErrors(err);
                    resolve(false);
                    return of(false);
                })
            ).subscribe();
        });
    }

    openResourceVersion(version: number, type: string) {

        const title = type !== 'PDF' ? this.translate.instant('lang.' + type + '_version') : `${this.translate.instant('lang.version')} ${version}`;

        // TO SHOW ORIGINAL DOC (because autoload signed doc)
        type = type === 'SIGN' ? 'PDF' : type;

        this.http.get(`../rest/resources/${this.resId}/content/${version}?type=${type}`).pipe(
            tap((data: any) => {

                this.dialog.open(DocumentViewerModalComponent, { autoFocus: false, panelClass: 'maarch-full-height-modal', data: { title: `${title}`, base64: data.encodedDocument } });
            }),
            catchError((err: any) => {
                this.notify.handleSoftErrors(err);
                return of(false);
            })
        ).subscribe();
    }

    unsignMainDocument() {
        this.dialogRef = this.dialog.open(ConfirmComponent, { panelClass: 'maarch-modal', autoFocus: false, disableClose: true, data: { title: this.translate.instant('lang.UNSIGN'), msg: this.translate.instant('lang.confirmAction') } });

        this.dialogRef.afterClosed().pipe(
            filter((data: string) => data === 'ok'),
            exhaustMap(() => this.http.put(`../rest/resources/${this.resId}/unsign`, {})),
            tap(() => {
                this.notify.success(this.translate.instant('lang.documentUnsigned'));
                this.loadRessource(this.resId);
            }),
            catchError((err: any) => {
                this.notify.handleErrors(err);
                return of(false);
            })
        ).subscribe();
    }

    isEditorLoaded() {
        if (this.isEditingTemplate()) {
            return this.isEditingTemplate() && this.isDocModified;
        } else {
            return true;
        }
    }

    openMaarchParapheurWorkflow() {
        this.dialog.open(VisaWorkflowModalComponent, { panelClass: 'maarch-modal', data: { id: this.resId, type: 'resource' } });
    }
}
