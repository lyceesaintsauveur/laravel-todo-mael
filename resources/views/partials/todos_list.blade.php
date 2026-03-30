@forelse ($todos as $todo)
    <li class="list-group-item">
        @if ($todo->important == 0)
            <i class="bi bi-reception-1"></i>
        @elseif ($todo->important == 1)
            <i class="bi bi-reception-4"></i>
        @endif
        <span>{{ $todo->texte }}</span>
        </br>
        <span>a faire avant le :{{ $todo->date_fin }}</span>
            
        @if (!empty($todo->categories) && $todo->categories->count() > 0)
            <div class="form-group">
                <label><i class="bi bi-boxes">categories</label>
                @foreach($todo->categories as $category)
                    <span>{{$category->libelle}}</span>  
                @endforeach
            </div>
        @endif   
        
        @if ($todo->listes)
            <p><i class="bi bi-list"></i> Appartient a la liste : {{ $todo->listes->libelle }}</p>
        @endif 

        @if ($todo->termine === 0)
            <a href="{{ route('todo.done', ['id' => $todo->id]) }}" class="btn btn-success"><i class="bi bi-check2-square"></i></a>
        @elseif ($todo->termine === 1)
            <a href="{{ route('todo.delete', ['id' => $todo->id]) }}" class="btn btn-danger btn-delete-todo" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" data-delete-url="{{ route('todo.delete', ['id' => $todo->id]) }}"><i class="bi bi-trash3"></i></i></a>
        @endif

        @if ($todo->important == 0)
            <a href="{{ route('todo.raise', ['id'=> $todo->id]) }}"><i class="bi bi-arrow-up-circle"></i></a>
        @elseif ($todo->important == 1)
            <a href="{{ route('todo.lower', ['id' => $todo->id]) }}"><i class="bi bi-arrow-down-circle"></i></a>
        @endif
    </li>
@empty
    <li class="list-group-item text-center">C'est vide !</li>
@endforelse
