@extends('layouts.main')
@section('content')
    <h1>New descriptor from {{ $person->full_name }}</h1>

    <form action="{{ route('persons.descriptors.store', $person) }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="photo" class="form-label">Photo</label>
            <input type="file" name="photo" id="photo" required accept="image/*"
                   class="form-control @error('last_name') is-invalid @enderror"/>
            @error('photo')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            <div class="text-center">
                <img src="" alt="" style="display:none;width:100px" id="photoContainer" class="mt-3"/>
            </div>
        </div>

        <input type="hidden" name="descriptor" id="descriptor"/>

        <div class="text-center">
            <button class="btn btn-success" disabled id="submitForm">Save</button>
        </div>
    </form>
@endsection

@push('styles')
    <script src="{{ asset('dist/face-api.js') }}"></script>
@endpush

@push('scripts')
    <script>
        const photoContainer = document.getElementById('photoContainer')
        const photoInput = document.getElementById('photo')

        photoInput.addEventListener('change', async function () {
            photoContainer.style.display = 'none'
            photoContainer.src = ''
            const [file] = this.files
            document.getElementById('submitForm').disabled = true

            if (file) {
                photoContainer.style.display = 'block'
                photoContainer.src = URL.createObjectURL(file)

                const descriptor = await updateReferenceImageResults()
                if (descriptor === false) {
                    alert('Не удалось определить дескриптор')
                }

                document.getElementById('descriptor').value = descriptor
                document.getElementById('submitForm').disabled = false
            }
        })

        async function updateReferenceImageResults() {
            if (!faceapi.nets.ssdMobilenetv1.params) {
                await run()
            }

            const fullFaceDescription = await faceapi
                .detectSingleFace(photoContainer)
                .withFaceLandmarks()
                .withFaceDescriptor()

            if (!fullFaceDescription) {
                return false
            }

            return faceapi.resizeResults(fullFaceDescription, photoContainer).descriptor ?? false
        }

        async function run() {
            if (!faceapi.nets.ssdMobilenetv1.params) {
                await faceapi.nets.ssdMobilenetv1.load('/weights')
                await faceapi.loadFaceLandmarkModel('/weights')
                await faceapi.loadFaceRecognitionModel('/weights')
            }
        }
    </script>
@endpush
