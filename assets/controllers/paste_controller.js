import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
export default class extends Controller {
    static targets = ['toSort']

    connect() {
        document.addEventListener("paste", async (event) => {
            const response = await fetch('/articles', {
                method: 'POST',
                body: JSON.stringify({
                    url: event.clipboardData.getData('text/plain')
                })
            })

            this.toSortTarget.innerHTML = await response.text()
        });
    }
}
