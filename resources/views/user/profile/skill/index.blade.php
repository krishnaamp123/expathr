    <div class="card">
        <div class="card-header" style="color: white;">Skill</div>
        <div class="card-body">
            <div class="">

                @if ($skill->isNotEmpty())
                    <ul class="list-group">
                        @foreach ($skill as $skilll)
                        <li class="list-group-item city-item position-relative">
                            <span class="kaem-subheading">{{ $skilll->skill_name ?? 'Unknown Skill' }}</span><br>
                            <div class="city-hover d-flex justify-content-end position-absolute top-0 start-0 w-100 h-100 align-items-center" style="display: none; background-color: rgba(35, 34, 34, 0.5)">

                                <button type="button" class="btn btn-sm btn-warning me-2" data-toggle="modal" data-target="#editSkillModal{{ $skilll->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('destroySkill', $skilll->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <p class="kaem-subheading">No skill added yet.</p>
                @endif

            <button type="button" class="btn btn-primary kaem-subheading mt-2" data-toggle="modal" data-target="#addSkillModal">
                Add Skill
            </button>
        </div>
        </div>
    </div>

@foreach ($skill as $skilll)
<!-- Modal Edit Work Location -->
<div class="modal fade" id="editSkillModal{{ $skilll->id }}" tabindex="-1" role="dialog" aria-labelledby="editSkillLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSkillLabel">Edit Skill</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editSkillForm" method="POST" action="{{ route('updateSkill', $user->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="skill_name" class="kaem-subheading">Skill Name</label>
                        <input type="text" class="form-control" id="skill_name" name="skill_name"  value="{{ $skilll->skill_name }}" required>
                        @error('skill_name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary kaem-subheading">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal Add Work Location -->
<div class="modal fade" id="addSkillModal" tabindex="-1" role="dialog" aria-labelledby="addSkillLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSkillLabel">Add Skill Contact</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @include('user.profile.skill.store')
            </div>
        </div>
    </div>
</div>
