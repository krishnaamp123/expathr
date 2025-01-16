    <div class="card">
        <div class="card-header" style="color: white;">Social</div>
        <div class="card-body">
            <div class="">

                @if ($link->isNotEmpty())
                    <ul class="list-group">
                        @foreach ($link as $linkk)
                        <li class="list-group-item city-item position-relative">
                            <span class="kaem-subheading">{{ ucwords(str_replace('_', ' ', $linkk->media)) ?? 'Unknown Social' }}</span><br>
                            <div class="city-hover d-flex justify-content-end position-absolute top-0 start-0 w-100 h-100 align-items-center" style="display: none; background-color: rgba(35, 34, 34, 0.5)">

                                <button type="button" class="btn btn-sm btn-warning me-2" data-toggle="modal" data-target="#editLinkModal{{ $linkk->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('destroyLink', $linkk->id) }}" method="POST" style="display: inline;">
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
                    <p class="kaem-subheading">No link added yet.</p>
                @endif

            <button type="button" class="btn btn-primary kaem-subheading mt-2" data-toggle="modal" data-target="#addLinkModal">
                Add Link
            </button>
        </div>
        </div>
    </div>

@foreach ($link as $linkk)
<!-- Modal Edit Work Location -->
<div class="modal fade" id="editLinkModal{{ $linkk->id }}" tabindex="-1" role="dialog" aria-labelledby="editLinkLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLinkLabel">Edit Social</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editLinkForm" method="POST" action="{{ route('updateLink', $linkk->id) }}">
                    @csrf
                    @method('PUT')

                    <label class="kaem-text">Social media that you have!</label>
                    <div class="form-group">
                        <label for="media" class="kaem-subheading">Social</label>
                        <select name="media" class="form-control select2">
                            <option value="">Select Social</option>
                            <option value="facebook" {{ $linkk->media == 'facebook' ? 'selected' : '' }}>Facebook</option>
                            <option value="instagram" {{ $linkk->media == 'instagram' ? 'selected' : '' }}>Instagram</option>
                            <option value="x" {{ $linkk->media == 'x' ? 'selected' : '' }}>X</option>
                            <option value="linkedin" {{ $linkk->media == 'linkedin' ? 'selected' : '' }}>Linkedin</option>
                            <option value="portofolio" {{ $linkk->media == 'portofolio' ? 'selected' : '' }}>Portofolio</option>
                            <option value="tiktok" {{ $linkk->media == 'tiktok' ? 'selected' : '' }}>Tiktok</option>
                        </select>
                        @error('media')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="media_url" class="kaem-subheading">Url</label>
                        <input type="url" class="form-control" id="media_url" name="media_url"  value="{{ $linkk->media_url }}" required>
                        @error('media_url')
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
<div class="modal fade" id="addLinkModal" tabindex="-1" role="dialog" aria-labelledby="addLinkLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLinkLabel">Add Social</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @include('user.profile.link.store')
            </div>
        </div>
    </div>
</div>
