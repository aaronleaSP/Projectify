document.addEventListener("DOMContentLoaded", function() {
    // Page 1: Button click handler
    document.getElementById('page1').addEventListener('click', function() {
        // You can add your logic here for the button click on the first page
        alert('Button clicked! You can add more functionality here.');
    });

    // Page 2: Smooth scrolling to the next section
    document.getElementById('page2').addEventListener('click', function() {
        const page3 = document.getElementById('page3');
        window.scrollTo({
            top: page3.offsetTop,
            behavior: 'smooth'
        });
    });
});

function redirectToLoginPage() {
    // Assuming your login page is named "login.html"
    window.location.href = "Login.php";
}