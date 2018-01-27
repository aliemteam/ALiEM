import 'intersection-observer';

(() => {
    const images: NodeListOf<HTMLImageElement> = document.querySelectorAll(
        'img[data-lazy-src]',
    );
    let imageCount = images.length;

    const observer = new IntersectionObserver(onIntersection, {
        rootMargin: '50px 0px',
        threshold: 0.01,
    });

    // forEach() is not supported in IE
    // tslint:disable-next-line:prefer-for-of
    for (let i = 0; i < images.length; i++) {
        observer.observe(images[i]);
    }

    function fetchImage(img: HTMLImageElement) {
        img.src = img.dataset.lazySrc!;
        img.addEventListener('load', onLoad);
        img.addEventListener('error', onError);
        function onLoad(this: HTMLImageElement) {
            this.classList.add('fade-in');
            this.removeEventListener('load', onLoad);
        }
        function onError(this: HTMLImageElement) {
            this.removeEventListener('error', onError);
        }
    }

    function onIntersection(entries: IntersectionObserverEntry[]) {
        // Disconnect if we've already loaded all of the images
        if (imageCount === 0) {
            observer.disconnect();
        }

        for (const entry of entries) {
            if (entry.isIntersecting) {
                imageCount--;
                observer.unobserve(entry.target);
                fetchImage(<HTMLImageElement>entry.target);
            }
        }
    }
})();
