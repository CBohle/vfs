document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");

    // CONSTANTES
  
    // Constantes campos de texto
    const camposTexto = [
        // Campo 1 
        { id: "nombre", min: 2, max: 50, soloLetras: true, label: "El nombre" },
        // Campo 2
        { id: "apellido", min: 2, max: 50, soloLetras: true, label: "El apellido" },
        // Campo 7
        { id: "direccion", min: 2, max: 100, soloLetras: false, label: "La dirección" },
        // Campo 8
        { id: "comuna", min: 2, max: 50, soloLetras: true, label: "La comuna" },
        // Campo 11
        { id: "institucion", min: 2, max: 100, soloLetras: false, label: "La institución" },
        // Campo 15
        { id: "otra_empresa", min: 1, max: 100, soloLetras: false, label: "Este campo" },
        // Campo adicional 13.1
        { id: "detalle_formacion", min: 1, max: 100, soloLetras: false, label: "Este campo" }
    ];
    // Constantes campos select
    const selects = [
        // Campo 9
        { id: "region", mensaje: "La región es obligatoria." },
        // Campo 10
        { id: "estudios", mensaje: "Debe seleccionar una carrera." },
        // Campo 13
        { id: "formacion_tasacion", mensaje: "Debe indicar si tiene formación en tasación." },
        // Campo 14
        { id: "ano_experiencia", mensaje: "Debe seleccionar su nivel de experiencia." },
        // Campo 16
        { id: "disponibilidad_comunal", mensaje: "Debe indicar si tiene disponibilidad para tasar en otras comunas dentro de su región." },
        // Campo 17
        { id: "disponibilidad_regional", mensaje: "Debe indicar si tiene disponibilidad para tasar en otras regiones dentro del país." },
        // Campo 18
        { id: "movilizacion", mensaje: "Debe indicar si cuenta con movilización propia para trasladarse a realizar tasaciones." }
    ];
    // Constantes otros campos
    // Campo 3
    const campoFecha = document.getElementById("fecha_nacimiento");
    // Campo 4
    const campoRut = document.getElementById("rut");
    // Campo 5
    const campoEmail = document.getElementById("email");
    // Campo 6
    const campoTelefono = document.getElementById("telefono");
    // Campo 12
    const campoAno = document.getElementById("ano_titulacion");
    // Campo 19
    const campoCV = document.getElementById("cv");

    // FUNCIONES DE VALIDACIÓN
    // Validación campos de texto
    function validarCampoTexto(input, min, max, soloLetras, label) {
        const valor = input.value.trim();
        limpiarError(input);
        if (valor.length < min) return mostrarError(input, `${label} debe tener al menos ${min} caracteres.`);
        if (valor.length > max) return mostrarError(input, `${label} no debe exceder los ${max} caracteres.`);
        if (soloLetras && !/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(valor)) {
            return mostrarError(input, `${label} solo puede contener letras y espacios.`);
        }
        if (/[<>"]/.test(valor)) {
            return mostrarError(input, `${label} no puede contener caracteres como <, > o ".`);
        }
        return true;
    }
    // Validación campos select
    function validarSelectObligatorio(select, mensaje) {
        limpiarError(select);
        if (!select.value) return mostrarError(select, mensaje);
        return true;
    }
    // Validaciones otros campos
    function validarEmail(input) {
        const valor = input.value.trim();
        limpiarError(input);
        if (!valor) return mostrarError(input, "El correo es obligatorio.");
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(valor)) return mostrarError(input, "El correo ingresado no es válido.");
        return true;
    }

    function validarTelefono(input) {
        const valor = input.value.trim();
        limpiarError(input);
        if (!valor) return mostrarError(input, "El número de teléfono es obligatorio.");
        if (!/^[0-9+\s()-]{7,20}$/.test(valor)) {
            return mostrarError(input, "El teléfono solo puede contener números, espacios, paréntesis, guiones o '+'.");
        }
        return true;
    }

    function validarFecha(input) {
        limpiarError(input);
        if (!input.value) return mostrarError(input, "La fecha de nacimiento es obligatoria.");
        const fecha = new Date(input.value);
        const hoy = new Date();
        if (fecha > hoy) return mostrarError(input, "La fecha de nacimiento no puede ser futura.");
        if (fecha.getFullYear() < 1900) return mostrarError(input, "Ingrese una fecha válida.");
        return true;
    }

    function validarRutCampo(input) {
        const valor = input.value.trim();
        limpiarError(input);
        if (!valor) return mostrarError(input, "El RUT es obligatorio.");
        if (!/^[0-9]{7,8}-[kK0-9]$/.test(valor)) return mostrarError(input, "El RUT debe tener el formato correcto, por ejemplo: 12345678-9.");
        if (!validarRut(valor)) return mostrarError(input, "El RUT ingresado no es válido.");
        return true;
    }

    function validarRut(rutCompleto) {
        rutCompleto = rutCompleto.toLowerCase();
        const [rut, dv] = rutCompleto.split("-");
        let suma = 0, multiplo = 2;
        for (let i = rut.length - 1; i >= 0; i--) {
            suma += parseInt(rut.charAt(i)) * multiplo;
            multiplo = multiplo < 7 ? multiplo + 1 : 2;
        }
        const dvEsperado = 11 - (suma % 11);
        const dvCalculado = dvEsperado === 11 ? "0" : dvEsperado === 10 ? "k" : dvEsperado.toString();
        return dv === dvCalculado;
    }

    function validarAnoTitulacion(input) {
        const valor = input.value.trim();
        limpiarError(input);
        if (!valor) return mostrarError(input, "El año de titulación es obligatorio.");
        const ano = parseInt(valor, 10);
        if (isNaN(ano) || ano < 1950 || ano > 2025) {
            return mostrarError(input, "El año debe estar entre 1950 y 2025.");
        }
        return true;
    }

    function validarArchivoCV(input) {
        limpiarError(input);

        const archivo = input.files[0];
        if (!archivo) return mostrarError(input, "Debe adjuntar su Currículum Vitae.");

        const tiposPermitidos = [
            "application/pdf",
            "application/msword",
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document"
        ];

        if (!tiposPermitidos.includes(archivo.type)) {
            return mostrarError(input, "Solo se permiten archivos PDF o Word (.doc, .docx).");
        }

        const maxSizeBytes = 2 * 1024 * 1024; // 2 MB
        if (archivo.size > maxSizeBytes) {
            return mostrarError(input, "El archivo no debe superar los 2 MB.");
        }

        return true;
    }

    // FUNCIÓN MOSTRAR ERROR
    function mostrarError(input, mensaje) {
        input.classList.add("is-invalid");
        let error = input.parentNode.querySelector(".invalid-feedback");
        if (error) error.innerText = mensaje;
        else {
            error = document.createElement("div");
            error.className = "invalid-feedback";
            error.innerText = mensaje;
            input.parentNode.appendChild(error);
        }
        return false;
    }
    // FUNCIÓN LIMPIAR ERROR
    function limpiarError(input) {
        input.classList.remove("is-invalid");
        const error = input.parentNode.querySelector(".invalid-feedback");
        if (error) input.parentNode.removeChild(error);
    }

    // BLUR
    // Campos de texto
    camposTexto.forEach(({ id, min, max, soloLetras, label }) => {
        const input = document.getElementById(id);
        input.addEventListener("blur", () => validarCampoTexto(input, min, max, soloLetras, label));
    });
    // Campos select
    selects.forEach(({ id, mensaje }) => {
        const campo = document.getElementById(id);
        campo.addEventListener("blur", () => validarSelectObligatorio(campo, mensaje));
        campo.addEventListener("change", () => validarSelectObligatorio(campo, mensaje));
    });
    // Otros campos
    campoEmail.addEventListener("blur", () => validarEmail(campoEmail));
    campoTelefono.addEventListener("blur", () => validarTelefono(campoTelefono));
    campoFecha.addEventListener("blur", () => validarFecha(campoFecha));
    campoRut.addEventListener("blur", () => validarRutCampo(campoRut));
    campoAno.addEventListener("blur", () => validarAnoTitulacion(campoAno));
    campoCV.addEventListener("blur", () => validarArchivoCV(campoCV));
    campoCV.addEventListener("change", () => validarArchivoCV(campoCV));

    
    // Validación al enviar
    form.addEventListener("submit", function (e) {
        let valido = true;
        // Validación campo de texto
        camposTexto.forEach(({ id, min, max, soloLetras, label }) => {
            const input = document.getElementById(id);
            if (!validarCampoTexto(input, min, max, soloLetras, label)) valido = false;
        });
        // Validación select
        selects.forEach(({ id, mensaje }) => {
            const campo = document.getElementById(id);
            if (!validarSelectObligatorio(campo, mensaje)) valido = false;
        });
        // Validación otros campos
        if (!validarEmail(campoEmail)) valido = false;
        if (!validarTelefono(campoTelefono)) valido = false;
        if (!validarFecha(campoFecha)) valido = false;
        if (!validarRutCampo(campoRut)) valido = false;
        if (!validarAnoTitulacion(campoAno)) valido = false;
        if (!validarArchivoCV(campoCV)) valido = false;
        if (!valido) e.preventDefault();

    });    
});