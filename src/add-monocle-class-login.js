(function () {
    const login = document.getElementById("loginform");
    const register = document.getElementById("registerform");
    const lostpass = document.getElementById("lostpasswordform");
    const comment = document.getElementById("commentform");

    if (login) {
        login.classList.add("monocle-enriched");
    }

    if (register) {
        register.classList.add("monocle-enriched");
    }

    if (lostpass) {
        lostpass.classList.add("monocle-enriched");
    }

    if (comment) {
        comment.classList.add("monocle-enriched");
    }
})();