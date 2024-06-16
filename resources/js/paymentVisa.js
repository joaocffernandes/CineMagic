document.addEventListener('DOMContentLoaded', function () {
    const payment_type_field = document.getElementById("id_payment_type");
    const payment_ref_container = document.getElementById("payment_ref_container")
    const cvc_field = document.getElementById("cvc_field");
    
    if (payment_type_field && cvc_field) {
        // Função que controla a visibilidade do campo CVC
        var func = function () {
            cvc_field.style.display = payment_type_field.value === "VISA" ? "block" : "none";
            if (payment_type_field.value === "MBWAY") {
                payment_ref_container.querySelector('label').innerText = "Phone Number";
                payment_ref_container.querySelector('input').type = "tel";
            } else if (payment_type_field.value === "PAYPAL") {
                payment_ref_container.querySelector('label').innerText = "Email";
                payment_ref_container.querySelector('input').type = "email";
            } else if (payment_type_field.value === "VISA") {
                payment_ref_container.querySelector('label').innerText = "Card Number";
                payment_ref_container.querySelector('input').type = "text";
            }
        }

        // Adiciona o listener de mudança ao campo de tipo de pagamento
        payment_type_field.addEventListener('change', func);

        // Executa a função ao carregar para definir o estado inicial
        func();
    }
});
