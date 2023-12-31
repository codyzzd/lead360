<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- jQuery UI Autocomplete -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!-- trabalhar datas -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<!-- underscore -->
<script src="https://cdn.jsdelivr.net/npm/underscore@1.13.6/underscore-umd-min.js"></script>
<!-- axios -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<!-- clipboard -->
<script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.11/dist/clipboard.min.js"></script>
<!-- popper -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>

<script>
  function criarToast(titulo, mensagem, duracaoPersonalizada) {
    var duracaoPadrao = 5000; // Duração padrão em milissegundos (5 segundos)

    var toastEl = document.createElement('div');
    toastEl.className = 'toast';

    toastEl.innerHTML = `
            <div class="toast-header">
            <strong class="me-auto">${titulo}</strong>
            <small>Now</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">${mensagem}</div>
            `;

    var toastersEl = document.getElementById('toasters');
    toastersEl.appendChild(toastEl);

    var toast = new bootstrap.Toast(toastEl);

    // Define a duração do toast (duração personalizada ou padrão)
    var duracao = duracaoPersonalizada !== undefined ? duracaoPersonalizada : duracaoPadrao;

    setTimeout(function () {
      toast.hide();
    }, duracao);

    toast.show();
  }

  function criarToastmini(tipo, mensagem, duracaoPersonalizada) {
    var duracaoPadrao = 8000;

    var toastEl = document.createElement('div');
    toastEl.className = `toast align-items-center text-bg-${tipo} border-0`;

    toastEl.innerHTML = `
            <div class="d-flex">
            <div class="toast-body">
            ${mensagem}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            `;

    var toastersEl = document.getElementById('toasters');
    toastersEl.appendChild(toastEl);

    var toast = new bootstrap.Toast(toastEl);

    // Define a duração do toast (duração personalizada ou padrão)
    var duracao = duracaoPersonalizada !== undefined ? duracaoPersonalizada : duracaoPadrao;

    setTimeout(function () {
      toast.hide();
    }, duracao);

    toast.show();
  }


</script>