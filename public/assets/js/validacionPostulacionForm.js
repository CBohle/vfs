document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");

    // CONSTANTES
    // Otras constantes
    const camposTocados = {};

    // Constantes campos de texto
    const camposTexto = [
        // Campo 1 
        { id: "nombre", min: 2, max: 50, soloLetras: true, label: "El nombre" },
        // Campo 2
        { id: "apellido", min: 2, max: 50, soloLetras: true, label: "El apellido" },
        // Campo 7
        { id: "direccion", min: 2, max: 100, soloLetras: false, label: "La dirección" },
        // Campo 11
        { id: "institucion", min: 2, max: 100, soloLetras: false, label: "La institución" },
        // Campo 15
        { id: "otra_empresa", min: 2, max: 100, soloLetras: false, label: "Este campo" },
        // Campo adicional 13.1
        { id: "detalle_formacion", min: 2, max: 100, soloLetras: false, label: "Este campo" }
    ];
    // Constantes campos select
    const selects = [
        // Campo 8
        { id: "region", mensaje: "La región es obligatoria." },
        // Campo 9
        { id: "comuna", mensaje: "La comuna es obligatoria." },
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
            "application/pdf"
        ];

        if (!tiposPermitidos.includes(archivo.type)) {
            return mostrarError(input, "Solo se permiten archivos PDF.");
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
        input.addEventListener("blur", () => {
            camposTocados[input.id] = true;
            validarCampoTexto(input, min, max, soloLetras, label);
            revisarEstadoFormulario();
        });
    });
    // Campos select
    selects.forEach(({ id, mensaje }) => {
        const campo = document.getElementById(id);
        campo.addEventListener("blur", () => {
            camposTocados[campo.id] = true;
            validarSelectObligatorio(campo, mensaje);
            revisarEstadoFormulario();
        });
        campo.addEventListener("change", () => {
            camposTocados[campo.id] = true;
            validarSelectObligatorio(campo, mensaje);
            revisarEstadoFormulario();
        });
    });
    // Otros campos
    campoEmail.addEventListener("blur", () => {
        camposTocados[campoEmail.id] = true;
        validarEmail(campoEmail);
        revisarEstadoFormulario();
    });
    campoTelefono.addEventListener("blur", () => {
        camposTocados[campoTelefono.id] = true;
        validarTelefono(campoTelefono);
        revisarEstadoFormulario();
    });
    campoFecha.addEventListener("blur", () => {
        camposTocados[campoFecha.id] = true;
        validarFecha(campoFecha);
        revisarEstadoFormulario();
    });
    campoRut.addEventListener("blur", () => {
        camposTocados[campoRut.id] = true;
        validarRutCampo(campoRut);
        revisarEstadoFormulario();
    });
    campoAno.addEventListener("blur", () => {
        camposTocados[campoAno.id] = true;
        validarAnoTitulacion(campoAno);
        revisarEstadoFormulario();
    });
    campoCV.addEventListener("blur", () => {
        camposTocados[campoCV.id] = true;
        validarArchivoCV(campoCV);
        revisarEstadoFormulario();
    });
    campoCV.addEventListener("change", () => {
        camposTocados[campoCV.id] = true;
        validarArchivoCV(campoCV);
        revisarEstadoFormulario();
    });

    // FUNCIÓN PARA REVISAR QUE TODOS LOS CAMPOS ESTÉN VÁLIDOS, Y LUEGO ACTIVAR EL BOTÓN
    function revisarEstadoFormulario() {
        const formularioValido = esFormularioValido();
        const boton = document.querySelector('#submitButton'); // o el ID que uses
        boton.disabled = !formularioValido;
    }
    const camposObligatorios = [
        'nombre', 'apellido', 'direccion', 'comuna', 'institucion', 'otra_empresa', 'estudios', 'region', 'formacion_tasacion', 'ano_experiencia', 'disponibilidad_comunal', 'disponibilidad_regional', 'movilizacion', 'fecha_nacimiento', 'rut', 'email', 'telefono', 'ano_titulacion', 'cv'
    ];
    function esFormularioValido() {
        // Verificar que todos los campos obligatorios estén tocados
        for (const campoId of camposObligatorios) {
            if (!camposTocados[campoId]) {
                return false;
            }
        }
        // Verificar que todos los campos obligatorios estén sin errores y completos
        for (const campoId of camposObligatorios) {
            const campo = document.getElementById(campoId);
            if (!campo) continue;
            if (campo.classList.contains('is-invalid')) return false;
            if (!campo.value) return false;
        }
        return true;
    }

    // VALIDACIÓN AL ENVIAR
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
// COMUNAS PARA CAMPO SELECT
const regionesYComunas = {
    xv: ["Arica", "Camarones", "General Lagos", "Putre"],
    i: ["Alto Hospicio", "Camiña", "Colchane", "Huara", "Iquique", "Pica", "Pozo Almonte"],
    ii: ["Antofagasta", "Calama", "María Elena", "Mejillones", "Ollagüe", "San Pedro de Atacama", "Sierra Gorda", "Taltal", "Tocopilla"],
    iii: ["Alto del Carmen", "Caldera", "Chañaral", "Copiapó", "Diego de Almagro", "Freirina", "Huasco", "Tierra Amarilla", "Vallenar"],
    iv: ["Andacollo", "Canela", "Combarbalá", "Coquimbo", "Illapel", "La Higuera", "La Serena", "Los Vilos", "Monte Patria", "Ovalle", "Paiguano", "Punitaqui", "Río Hurtado", "Salamanca", "Vicuña"],
    v: ["Algarrobo", "Cabildo", "Calera", "Calle Larga", "Cartagena", "Casablanca", "Catemu", "Concón", "El Quisco", "El Tabo", "Hijuelas", "Isla de Pascua", "Juan Fernández", "La Cruz", "La ligua", "Limache", "Llaillay", "Los Andes", "Nogales", "Olmué", "Panquehue", "Papudo", "Petorca", "Puchuncaví", "Putaendo", "Quillota", "Quilpué", "Quintero", "Rinconada", "San Antonio", "San Esteban", "San Felipe", "Santa María", "Santo Domingo", "Valparaíso", "Villa Alemana", "Viña del Mar", "Zapallar"],
    rm: ["Alhué", "Buin", "Calera de Tango", "Cerrillos", "Cerro Navia", "Colina", "Conchalí", "Curacaví", "El Bosque", "El Monte", "Estación Central", "Huechuraba", "Independencia", "Isla de Maipo", "La Cisterna", "La Florida", "La Granja", "La Pintana", "La Reina", "Lampa", "Las Condes", "Lo Barnechea", "Lo Espejo", "Lo Prado", "Macul", "Maipú", "María Pinto", "Melipilla", "Ñuñoa", "Padre Hurtado", "Paine", "Pedro Aguirre Cerda", "Peñaflor", "Peñalolén", "Pirque", "Providencia", "Pudahuel", "Puente Alto", "Quilicura", "Quinta Normal", "Recoleta", "Renca", "San Bernardo", "San Joaquín", "San José de Maipo", "San Miguel", "San Pedro", "San Ramón", "Santiago", "Talagante", "Tiltil", "Vitacura"],
    vi: ["Chépica", "Chimbarongo", "Codegua", "Coinco", "Coltauco", "Doñihue", "Graneros", "La Estrella", "Las Cabras", "Litueche", "Lolol", "Machalí", "Malloa", "Marchihue", "Mostazal", "Nancagua", "Navidad", "Olivar", "Palmilla", "Paredones", "Peralillo", "Peumo", "Pichidegua", "Pichilemu", "Placilla", "Pumanque", "Quinta de Tilcoco", "Rancagua", "Rengo", "Requinoa", "San Fernando", "San Vicente", "Santa Cruz"],
    vii: ["Cauquenes", "Chanco", "Colbún", "Constitución", "Curepto", "Curicó", "Empedrado", "Hualañé", "Licantén", "Linares", "Longaví", "Maule", "Molina", "Parral", "Pelarco", "Pelluhue", "Pencahue", "Rauco", "Retiro", "Río Claro", "Romeral", "Sagrada Familia", "San Clemente", "San Javier", "San Rafael", "Talca", "Teno", "Vichuquén", "Villa Alegre", "Yerbas Buenas"],
    xvi: ["Bulnes", "Chillán", "Chillán Viejo", "Cobquecura", "Coelemu", "Coihueco", "El Carmen", "Ninhue", "Ñiquén", "Pemuco", "Pinto", "Portezuelo", "Quillón", "Quirihue", "Ránquil", "San Carlos", "San Fabián", "San Ignacio", "San Nicolás", "Treguaco", "Yungay"],
    viii: ["Alto Biobío", "Antuco", "Arauco", "Cabrero", "Cañete", "Chiguayante", "Concepción", "Contulmo", "Coronel", "Curanilahue", "Florida", "Hualpén", "Hualqui", "Laja", "Lebu", "Los Alamos", "Los Ángeles", "Lota", "Mulchén", "Nacimiento", "Negrete", "Penco", "Quilaco", "Quilleco", "San Pedro de la Paz", "San Rosendo", "Santa Bárbara", "Santa Juana", "Talcahuano", "Tirua", "Tomé", "Tucapel", "Yumbel"],
    ix: ["Angol", "Carahue", "Cholchol", "Collipulli", "Cunco", "Curacautín", "Curarrehue", "Ercilla", "Freire", "Galvarino", "Gorbea", "Lautaro", "Loncoche", "Lonquimay", "Los Sauces", "Lumaco", "Melipeuco", "Nueva Imperial", "Padre las Casas", "Pequenco", "Pitrufquén", "Pucón", "Purén", "Renaico", "Saavedra", "Temúco", "Teodoro Schmidt", "Toltén", "Traiguén", "Victoria", "Vilcún", "Villarrica"],
    xiv: ["Corral", "Futrono", "La Unión", "Lago Ranco", "Lanco", "Los Lagos", "Máfil", "Mariquina", "Paillaco", "Panguipulli", "Río Bueno", "Valdivia"],
    x: ["Ancud", "Calbuco", "Castro", "Chaitén", "Chonchi", "Cochamó", "Curaco de Vélez", "Dalcahue", "Fresia", "Frutillar", "Futaleufú", "Hualaihué", "Llanquihue", "Los Muermos", "Maullín", "Osorno", "Palena", "Puerto Montt", "Puerto Octay", "Puerto Varas", "Puqueldón", "Purranque", "Puyehue", "Queilén", "Quellón", "Quemchi", "Quinchao", "Río Negro", "San Juan de la Costa", "San Pablo"],
    xi: ["Aysén", "Chile Chico", "Cisnes", "Cochrane", "Coyhaique", "Guaitecas", "Lago Verde", "O'Higgins", "Ró Ibáñez", "Tortel"],
    xii: ["Antártica", "Cabo de Hornos", "Laguna Blanca", "Natales", "Porvenir", "Primavera", "Punta Arenas", "Río Verde", "San Gregorio", "Timaukel", "Torres del Paine"]
};

document.addEventListener("DOMContentLoaded", function () {
    const regionSelect = document.getElementById("region");
    const comunaSelect = document.getElementById("comuna");

    regionSelect.addEventListener("change", function () {
        const region = this.value;

        // Limpiar comuna
        comunaSelect.innerHTML = '<option value="" disabled selected>Seleccione una comuna</option>';

        if (region && regionesYComunas[region]) {
            regionesYComunas[region].forEach(comuna => {
                const option = document.createElement("option");
                option.value = comuna;
                option.textContent = comuna;
                comunaSelect.appendChild(option);
            });
        }
    });
});