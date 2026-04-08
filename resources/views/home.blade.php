
<!-- On appelle la template qui contient la navbar -->
@extends("template")

@section("title", "Ma Todo List")

@section("content")
<div class="container pt-4">
    <div class="card">
        <div class="card-body" x-data="{ 
            filter: '{{ $currentFilter }}',
            loading: false,
            fetchTodos(newFilter) {
                this.filter = newFilter;
                this.loading = true;
                // Met à jour l'URL sans recharger la page
                const url = new URL(window.location);
                url.searchParams.set('filter', newFilter);
                window.history.pushState({}, '', url);

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    document.getElementById('todos-list').innerHTML = html;
                    this.loading = false;
                });
            }
        }">
            <!-- Navigation des filtres -->
            <div class="btn-group mb-3 w-100" role="group" aria-label="Filtres ToDo">
                <button type="button" @click="fetchTodos('all')" 
                    :class="filter === 'all' ? 'btn btn-primary' : 'btn btn-outline-primary'"
                    class="btn">
                    Toutes
                </button>
                <button type="button" @click="fetchTodos('active')" 
                    :class="filter === 'active' ? 'btn btn-primary' : 'btn btn-outline-primary'"
                    class="btn">
                    En cours
                </button>
                <button type="button" @click="fetchTodos('completed')" 
                    :class="filter === 'completed' ? 'btn btn-primary' : 'btn btn-outline-primary'"
                    class="btn">
                    Terminées
                </button>
            </div>

            <!-- Action -->
            <form action="{{ route('todo.save') }}" method="POST" class="add">
                @csrf <!-- <<L'annotation ici ! -->
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1"><span class="oi oi-pencil"></span></span>
                    <input id="texte" name="texte" type="text" class="form-control" placeholder="Prendre une note..." aria-label="My new idea" aria-describedby="basic-addon1">
                    @if (session('message'))
                        <p class="alert alert-danger">{{ session('message') }}</p>
                    @endif
                </div>
                <label>listes</label>
                         <div class="form-group pt-2">
                    <select name="liste" id="liste">
                        <option value="NULL"></option>
                        @foreach($listes as $liste)
                            <option value="{{ $liste->id }}">{{ $liste->libelle }}</option>
                        @endforeach
                    </select>
                </div>
                <input type="datetime-local" name="date_fin">
                <!-- boites à cocher pour les catégories -->
                <div class="form-group pt-2">
                    <label>Catégories</label>
                        @foreach($categories as $categorie)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $categorie->id }}">
                                <label class="form-check-label">{{ $categorie->libelle }}</label>
                            </div>
                        @endforeach
                </div>
                <div class="priority-choice pt-2">
                    Importance : 
                    <input type="radio" name="priority" id="lowpr" value="0" checked><label for="lowpr"><i class="bi bi-reception-1"></i></label>
                    <input type="radio" name="priority" id="highpr" value="1"><label for="highpr"><i class="bi bi-reception-4"></i></label>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i></button>
                </div>
            </form>

            <!-- Liste -->
            <ul class="list-group" id="todos-list" :style="loading ? 'opacity: 0.5' : ''">
                @include('partials.todos_list')
            </ul>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmDeleteLabel">Confirmation de suppression</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        Êtes-vous sûr de vouloir supprimer ce todo ? Cette action est irréversible.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <a href="#" id="confirmDeleteUrl" class="btn btn-danger">Supprimer</a>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    var deleteModal = document.getElementById('confirmDeleteModal');
    var confirmLink = document.getElementById('confirmDeleteUrl');

    deleteModal.addEventListener('show.bs.modal', function (event) {
      var button = event.relatedTarget;
      var deleteUrl = button.getAttribute('data-delete-url');
      if (deleteUrl) {
        confirmLink.setAttribute('href', deleteUrl);
      }
    });
  });
</script>
@endsection