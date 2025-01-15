    <div class="card">
        <div class="card-header" style="color: white;">Skill</div>
        <div class="card-body">
            <div class="">

                @if ($workskill->isNotEmpty())
                    <ul class="list-group">
                        @foreach ($workskill as $skilll)
                        <li class="list-group-item city-item position-relative">
                            <span class="kaem-subheading">{{ $skilll->skill->skill_name ?? 'Unknown Skill' }}</span><br>
                            <div class="city-hover d-flex justify-content-end position-absolute top-0 start-0 w-100 h-100 align-items-center" style="display: none; background-color: rgba(35, 34, 34, 0.5)">

                                <button type="button" class="btn btn-sm btn-warning me-2" data-toggle="modal" data-target="#editSkillModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('destroyWorkSkill', $skilll->id) }}" method="POST" style="display: inline;">
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
                Add Preferred Skill
            </button>
        </div>
        </div>
    </div>

@foreach ($workskill as $skilll)
<!-- Modal Edit Work Location -->
<div class="modal fade" id="editSkillModal" tabindex="-1" role="dialog" aria-labelledby="editSkillLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSkillLabel">Edit Skill</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editSkillForm" method="POST" action="{{ route('updateWorkSkill', $skilll->id) }}">
                    @csrf
                    @method('PUT')
                    <label class="kaem-text">Select all the skills that you master!</label>
                    <div class="form-group">
                        <label for="id_skill" class="kaem-subheading">Skill</label>
                        <select name="id_skill" id="edit_id_skill" class="form-control select2" required>
                            <option value="">Select Skill</option>
                            @foreach ($skills as $skill)
                                <option value="{{ $skill->id }}">{{ $skill->skill_name }}</option>
                            @endforeach
                        </select>
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
                <h5 class="modal-title" id="addSkillLabel">Add Skill</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @include('user.profile.workskill.store', ['skills' => $skills])
            </div>
        </div>
    </div>
</div>
