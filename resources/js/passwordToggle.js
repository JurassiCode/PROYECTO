// ------------------------------------------------------
// Controla los botones de mostrar/ocultar contraseña
// en los formularios del sistema (login, registro, etc.)
// ------------------------------------------------------

document.addEventListener("DOMContentLoaded", () => {
  const toggles = [
    { btn: "togglePassword", input: "contrasena" },
    { btn: "togglePassword2", input: "contrasena_confirmation" },
  ];

  // Recorre cada par botón-input
  toggles.forEach(({ btn, input }) => {
    const b = document.getElementById(btn);
    const i = document.getElementById(input);

    if (b && i) {
      b.addEventListener("click", () => {
        const isPwd = i.type === "password";
        i.type = isPwd ? "text" : "password";

        // Cambia el icono visualmente
        const icon = b.querySelector("i");
        icon.classList.toggle("bi-eye");
        icon.classList.toggle("bi-eye-slash");
      });
    }
  });
});
