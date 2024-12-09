document.addEventListener("DOMContentLoaded", function() {
    function showErrorMessage(element, message) {
        let errorElement = document.createElement("div");
        errorElement.classList.add("text-danger", "mt-2");
        errorElement.innerText = message;
        element.parentElement.appendChild(errorElement);
    }

    function clearErrorMessage(element) {
        const errorElement = element.parentElement.querySelector(".text-danger");
        if (errorElement) {
            errorElement.remove();
        }
    }

    document.getElementById("sala").addEventListener("blur", function() {
        const sala = document.getElementById("sala");
        clearErrorMessage(sala);
        if (sala.value === "") {
            showErrorMessage(sala, "Por favor, selecciona una sala.");
        }
    });

    document.getElementById("num_sillas").addEventListener("blur", function() {
        const numSillas = document.getElementById("num_sillas");
        clearErrorMessage(numSillas);
        if (numSillas.value === "") {
            showErrorMessage(numSillas, "Por favor, selecciona el número de sillas.");
        }
    });
});
