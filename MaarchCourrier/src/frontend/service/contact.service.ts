import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { TranslateService } from '@ngx-translate/core';
import { tap, catchError, map } from 'rxjs/operators';
import { of } from 'rxjs';
import { FunctionsService } from './functions.service';

@Injectable()
export class ContactService {

    constructor(
        public translate: TranslateService,
        public http: HttpClient,
        public functions: FunctionsService 
    ) { }

    getFillingColor(thresholdLevel: 'first' | 'second' | 'third') {
        if (thresholdLevel === 'first') {
            return '#E81C2B';
        } else if (thresholdLevel === 'second') {
            return '#F4891E';
        } else if (thresholdLevel === 'third') {
            return '#0AA34F';
        } else {
            return '';
        }
    }

    formatCivilityObject(civility: any) {
        if (!this.empty(civility)) {
            return civility
        } else {
            return {
                label:'',
                abbreviation:''
            }
        }
    }

    formatFillingObject(filling: any) {
        if (!this.empty(filling)) {
            return {
                rate: filling.rate,
                color:  this.getFillingColor(filling.thresholdLevel)
            }
        } else {
            return {
                rate:'',
                color:''
            }
        }
    }

    empty(value: any) {
        if (value !== null && value !== '' && value !== undefined) {
            return false;
        } else {
            return true;
        }
    }

    formatContact(contact: any) {
        if (this.functions.empty(contact.firstname) && this.functions.empty(contact.lastname)) {
            return contact.company;

        } else {
            const arrInfo = [];
            arrInfo.push(contact.firstname);
            arrInfo.push(contact.lastname);
            if (!this.functions.empty(contact.company)) {
                arrInfo.push('(' + contact.company + ')');
            }

            return arrInfo.filter(info => info !== '').join(' ');
        }
    }
}
