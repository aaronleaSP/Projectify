const firebaseConfig = {
    apiKey: "AIzaSyBRobrPqyg9HTlTBPQlSMF8PjFQRRay9lI",
    authDomain: "projectify-sp.firebaseapp.com",
    projectId: "projectify-sp",
    storageBucket: "projectify-sp.appspot.com",
    messagingSenderId: "33617620423",
    appId: "1:33617620423:web:e4e27754a49c862434fde2",
    measurementId: "G-0ZQJX8V814"
};

firebase.initializeApp(firebaseConfig);
const auth = firebase.auth();

function signOut() {
    auth.signOut();
}

firebase.auth().onAuthStateChanged((user) => {
    if (user) {
        let username = user.displayName;

        let usertext = document.getElementById("user");
        usertext.innerText = username;

        localStorage.setItem("user", user.email);
    } else {
        window.location.href = "login.html";
    }
});