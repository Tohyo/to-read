import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    connect() {
        document.addEventListener("paste", (event) => {
            fetch('/articles', {
                method: 'POST',
                body: JSON.stringify({
                    url: event.clipboardData.getData('text/plain')
                })
            })
        });
    }
}
