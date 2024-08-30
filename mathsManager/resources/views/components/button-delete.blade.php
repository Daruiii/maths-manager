@props(['href'])
@props(['entity'])
@props(['entityId'])

<form method="POST" action="{{ $href }}" id="delete-form-{{ $entityId }}">
  @csrf
  @method('DELETE')
  <button type="button" class="delete-button" onclick="showDeleteConfirmation('{{ $entityId }}', '{{ $entity }}')">
    <svg class="delete-svgIcon" viewBox="0 0 448 512">
      <path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"></path>
    </svg>
  </button>
</form>

<script>
  function showDeleteConfirmation(entityId, entity) {
    Swal.fire({
      title: `Êtes-vous sûr de vouloir supprimer ${entity} ?`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Oui',
      cancelButtonText: 'Non',
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById(`delete-form-${entityId}`).submit();
      }
    });
  }
</script>

<style>
   .delete-button {
        width: 25px;
        height: 25px;
        margin : 0 0.05rem;
        border-radius: 50%;
        background-color: rgb(20, 20, 20);
        border: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.164);
        cursor: pointer;
        transition-duration: 0.3s;
        overflow: hidden;
        position: relative;
        text-decoration: none !important;
    }

    .delete-svgIcon {
        width: 12px;
        transition-duration: 0.3s;
    }

    .delete-svgIcon path {
        fill: white;
    }

    .delete-button:hover {
        width: 60px;
        border-radius: 50px;
        transition-duration: 0.3s;
        background-color: rgb(255, 69, 69);
        align-items: center;
    }

    .delete-button:hover .delete-svgIcon {
        width: 12px;
        transition-duration: 0.3s;
        transform: translateY(60%);
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        transform: rotate(360deg);
    }

    .delete-button::before {
        display: none;
        content: "Delete";
        color: white;
        transition-duration: 0.3s;
        font-size: 2px;
    }

    .delete-button:hover::before {
        display: block;
        padding-right: 10px;
        font-size: 10px;
        opacity: 1;
        transform: translateY(0px);
        transition-duration: 0.3s;
    }
</style>