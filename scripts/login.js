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

let signUpBool = false;

function signUp() {
    let username = document.getElementById("usernameregister").value;
    let email = document.getElementById("emailregister").value;
    let password = document.getElementById("passwordregister").value;
    let confirmpassword = document.getElementById("confirmpasswordregister").value;

    let textError = document.getElementById("textError");
    let brError = document.getElementById("brError");

    let usernameRegex = /^[a-zA-Z0-9\s]+$/;

    if (usernameRegex.test(username)) {
        if (password === confirmpassword) {
            const promise = auth.createUserWithEmailAndPassword(
                email,
                password
            );

            signUpBool = true;
            promise.then((userCredential) => {
                const user = userCredential.user;

                user.updateProfile({displayName: username}).then(() => {
                    const promise2 = auth.signInWithEmailAndPassword(
                        email,
                        password);

                    promise2.then(() => {
                        textError.innerText = "";
                        brError.style.display = "none";
                    }).catch((e) => {
                        if (e.code === "auth/internal-error") {
                            textError.innerText = "Invalid credentials entered";
                            brError.style.display = "block";
                        } else console.log(e.code);
                        signUpBool = false;
                    });
                }).catch((e) => {
                    signUpBool = false;
                    console.log(e.code);
                });
            }).catch((e) => {
                signUpBool = false;
                textError.innerText = e.message;
                brError.style.display = "block";
            });
        } else {
            textError.innerText = "Passwords do not match!";
            brError.style.display = "block";
        }
    } else {
        textError.innerText = "Username can only contain alphabets and spaces!";
        brError.style.display = "block";
    }
}

function logIn() {
    let email = document.getElementById("emaillogin").value;
    let password = document.getElementById("passwordlogin").value;

    let textError = document.getElementById("textError");
    let brError = document.getElementById("brError");

    const promise = auth.signInWithEmailAndPassword(
        email,
        password);

    promise.then(() => {
        textError.innerText = "";
        brError.style.display = "none";
    }).catch((e) => {
        if (e.code === "auth/internal-error") {
            textError.innerText = "Invalid credentials entered";
            brError.style.display = "block";
        } else console.log(e.code);
    });
}

function signInWithGoogle() {
    const provider = new firebase.auth.GoogleAuthProvider();
    auth.signInWithPopup(provider)
        .catch((error) => {
            console.log(error.message);
        });
}

firebase.auth().onAuthStateChanged((user) => {
    if (user) {
        if (signUpBool) {
            setTimeout(() => {
                window.location.href = "dashboard.html";
            }, 1000);
        } else window.location.href = "dashboard.html";
    }
});