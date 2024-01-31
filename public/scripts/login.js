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

    let errorMessage = document.getElementById("error-message");
    let textError = document.getElementById("textError");

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
                        errorMessage.style.display = "none";
                    }).catch((e) => {
                        if (e.code === "auth/internal-error") {
                            textError.innerText = "Invalid credentials entered";
                            errorMessage.style.display = "block";
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
                errorMessage.style.display = "block";
            });
        } else {
            textError.innerText = "Passwords do not match";
            errorMessage.style.display = "block";
        }
    } else {
        textError.innerText = "Username can only contain alphabets and spaces";
        errorMessage.style.display = "block";
    }
}

function logIn() {
    let email = document.getElementById("emaillogin").value;
    let password = document.getElementById("passwordlogin").value;

    let errorMessage = document.getElementById("error-message");
    let textError = document.getElementById("textError");

    const promise = auth.signInWithEmailAndPassword(
        email,
        password);

    promise.then(() => {
        textError.innerText = "";
        errorMessage.style.display = "none";
    }).catch((e) => {
        if (e.code === "auth/invalid-email") {
            textError.innerText = "Invalid email";
        } else if (e.code === "auth/wrong-password" || e.code === "auth/internal-error") {
            textError.innerText = "Wrong password";
        } else {
            textError.innerText = e.code;
        }
        errorMessage.style.display = "block";
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