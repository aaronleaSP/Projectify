document.addEventListener("DOMContentLoaded", function () {
    const pages = document.querySelectorAll('.page');
    let currentPage = 0;

    document.addEventListener('wheel', function (event) {
        if (event.deltaY > 0 && currentPage < pages.length - 1) {
            currentPage++;
        } else if (event.deltaY < 0 && currentPage > 0) {
            currentPage--;
        }

        scrollToPage(currentPage);
    });

    function scrollToPage(index) {
        const scrollTo = index * window.innerHeight;
        document.body.style.transform = `translateY(-${scrollTo}px)`;
    }
});
