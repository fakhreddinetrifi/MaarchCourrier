import { Pipe, PipeTransform } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { AuthService } from '@service/auth.service';
import { Observable } from 'rxjs';

@Pipe({
    name: 'secureUrl'
})
export class SecureUrlPipe implements PipeTransform {

    constructor(
        private http: HttpClient,
        private authService: AuthService
    ) { }

    transform(url: string) {
        const headers = new HttpHeaders({
            'Authorization': 'Bearer ' + this.authService.getToken()
        });

        return new Observable<string>((observer) => {
            // This is a tiny blank image
            observer.next('data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==');

            if (url !== undefined) {
                // The next and error callbacks from the observer
                const { next, error } = observer;

                this.http.get(url, { headers: headers, responseType: 'blob' }).subscribe(response => {
                    const reader = new FileReader();
                    reader.readAsDataURL(response);
                    reader.onloadend = () => {
                        observer.next(reader.result as any);
                    };
                });
            }
            return { unsubscribe() { } };
        });
    }
}
