<div class="wh">
  <a href="https://api.whatsapp.com/send?phone=5571991269354&text=Preciso%20de%20ajuda" target="black"><img src="dist/images/icone_wh.png" alt=""></a>
</div>

<!-- 
**********************
****   SCRIPTS   *****
**********************
-->

<!-- TABLE -->
<script src="dist/js/table/jquery-3.5.1.js"></script>
<script type="text/javascript" src="dist/js/table/datatables.min.js"></script>
<script>
  var table$ = jQuery.noConflict();
  table$(document).ready(function() {
    table$('#tabela').DataTable({
      // "order": [[ 1, 'asc' ]],
      "lengthMenu": [
        [10, 15, 20, 25, 30, 50, 100, -1],
        [10, 15, 20, 25, 30, 50, 100, "Todos"]
      ],
      "language": {
        "sProcessing": "Procurando...",
        "lengthMenu": "Mostrar _MENU_ registros",
        "zeroRecords": "Nenhum registro encontrado",
        "search": "Procurar: ",
        "info": "Mostrar _START_ até _END_ de _TOTAL_ registros",
        "infoEmpty": "Nenhum registro encontrad",
        "infoFiltered": "(filtrado de _MAX_ registros totais)",
        "paginate": {
          "first": "Primeiro",
          "last": "Último",
          "next": "Próximo",
          "previous": "Anterior"
        },
      }
    });

    table$('#financeiro').DataTable({
      "order": [
        [1, 'desc']
      ],
      "lengthMenu": [
        [10, 15, 20, 25, 30, 50, 100, -1],
        [10, 15, 20, 25, 30, 50, 100, "Todos"]
      ],
      "language": {
        "sProcessing": "Procurando...",
        "lengthMenu": "Mostrar _MENU_ registros",
        "zeroRecords": "Nenhum registro encontrado",
        "search": "Procurar: ",
        "info": "Mostrar _START_ até _END_ de _TOTAL_ registros",
        "infoEmpty": "Nenhum registro encontrad",
        "infoFiltered": "(filtrado de _MAX_ registros totais)",
        "paginate": {
          "first": "Primeiro",
          "last": "Último",
          "next": "Próximo",
          "previous": "Anterior"
        },
      }
    });


    table$('#pedidos').DataTable({
      "order": [
        [3, 'desc']
      ],
      "lengthMenu": [
        [10, 15, 20, 25, 30, 50, 100, -1],
        [10, 15, 20, 25, 30, 50, 100, "Todos"]
      ],
      "language": {
        "sProcessing": "Procurando...",
        "lengthMenu": "Mostrar _MENU_ registros",
        "zeroRecords": "Nenhum registro encontrado",
        "search": "Procurar: ",
        "info": "Mostrar _START_ até _END_ de _TOTAL_ registros",
        "infoEmpty": "Nenhum registro encontrad",
        "infoFiltered": "(filtrado de _MAX_ registros totais)",
        "paginate": {
          "first": "Primeiro",
          "last": "Último",
          "next": "Próximo",
          "previous": "Anterior"
        },
      }
    });
  });
</script>

<!-- CONFIRMA EXCLUIR -->
<!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
  var sweet$ = jQuery.noConflict();
  sweet$('.del-btn').on('click', function(e) {
    e.preventDefault();
    const href = sweet$(this).attr('href')
    Swal.fire({
      text: 'Deseja excluir este resgistro?',
      // title: "You won't be able to revert this!",
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Excluir',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.value) {
        document.location.href = href;
      }
    })
  })

  sweet$('.del-btn-cliente').on('click', function(e) {
    e.preventDefault();
    const href = sweet$(this).attr('href')
    Swal.fire({
      text: 'Ao excluir este cliente, todos os usuários atrelados a ele serão perdidos. Deseja continuar?',
      // title: "You won't be able to revert this!",
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Excluir',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.value) {
        document.location.href = href;
      }
    })
  })

  sweet$('.dup_pedido').on('click', function(e) {
    e.preventDefault();
    const href = sweet$(this).attr('href')
    Swal.fire({
      text: 'Deseja solicitar este pedido novamente?',
      // title: "You won't be able to revert this!",
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Solicitar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.value) {
        document.location.href = href;
      }
    })
  })


  sweet$('.conc_nc').on('click', function(e) {
    e.preventDefault();
    const href = sweet$(this).attr('href')
    Swal.fire({
      text: 'Deseja concluir este atendimento?',
      // title: "You won't be able to revert this!",
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Concluir',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.value) {
        document.location.href = href;
      }
    })
  })
</script>



<!-- APAGA MENSAGEM ALERTA APÓS 5 SEGUNDOS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
  var alert = jQuery.noConflict();
  window.setTimeout(function() {
    alert(".msg_alerta").fadeTo(500, 0).slideUp(500, function() {
      alert(this).remove();
    });
  }, 5000);
</script>

<!-- PRELOADER -->
<script>
  var loard$ = jQuery.noConflict();
  loard$(window).on('load', function() {
    loard$('#preloader .inner').fadeOut();
    loard$('#preloader').delay(350).fadeOut('slow');
    loard$('body').delay(350).css({
      'overflow': 'visible'
    });
  })
</script>

<!-- BOOTSTRAP -->
<script src="dist/js/table/jquery-3.5.1.js"></script>
<script src="dist/js/bootstrap/bootstrap.bundle.min.js"></script>

</body>

</html>