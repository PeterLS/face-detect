@extends('layouts.main')
@section('content')
    <div class="mx-auto position-relative" style="width:1000px">
        <video onloadedmetadata="onPlay(this)" id="inputVideo" autoplay muted playsinline class="w-100"></video>
        <canvas id="overlay" class="mx-auto" style="width:1000px"/>
    </div>

    <!-- fps_meter -->
    <div id="fps_meter" class="mx-auto text-start position-absolute top-0 end-0 pt-2 pe-2">
        <small class="text-secondary">
            <b>Time:</b> <span id="time">-</span><br/>
            <b>Estimated Fps:</b> <span id="fps">-</span>
        </small>
    </div>
    <!-- fps_meter -->
@endsection

@push('styles')
    <script src="{{ asset('dist/face-api.js') }}"></script>
@endpush

@push('scripts')
    <script>
        let forwardTimes = []
        let faceMatcher = false
        const videoEl = document.getElementById('inputVideo')

        function updateTimeStats(timeInMs) {
            forwardTimes = [timeInMs].concat(forwardTimes).slice(0, 30)
            const avgTimeInMs = forwardTimes.reduce((total, t) => total + t) / forwardTimes.length
            document.getElementById('time').innerText = `${Math.round(avgTimeInMs)} ms`
            document.getElementById('fps').innerText = `${faceapi.utils.round(1000 / avgTimeInMs, 0)}`
        }

        async function onPlay() {
            if (videoEl.paused || videoEl.ended || !faceapi.nets.ssdMobilenetv1.params) {
                return setTimeout(() => onPlay())
            }

            if (!faceMatcher) {
                await axios.get('/api/all-descriptors').then(({data}) => {
                    let faceMatcherArray = []
                    if (data.length) {
                        data.forEach((el) => {
                            faceMatcherArray.push(new faceapi.LabeledFaceDescriptors(el.label, el.descriptors.map(el => new Float32Array(el))))
                        })

                        faceMatcher = new faceapi.FaceMatcher(faceMatcherArray)
                    }
                })
            }

            const ts = Date.now()

            const results = await faceapi.detectAllFaces(videoEl)
                .withFaceLandmarks()
                .withFaceDescriptors()

            if (results.length) {
                const canvas = document.getElementById('overlay')

                const resizedResults = faceapi.resizeResults(results, faceapi.matchDimensions(canvas, videoEl, true))

                faceapi.draw.drawDetections(canvas, resizedResults)

                resizedResults.forEach(result => {
                    const {detection, descriptor} = result
                    const label = faceMatcher.findBestMatch(descriptor).toString()
                    const drawBox = new faceapi.draw.DrawBox(detection.box, {label})
                    drawBox.draw(canvas)
                })
            }

            updateTimeStats(Date.now() - ts)

            setTimeout(() => onPlay())
        }

        async function run() {
            if (!faceapi.nets.ssdMobilenetv1.params) {
                await faceapi.nets.ssdMobilenetv1.load('/weights')
                await faceapi.loadFaceLandmarkModel('/weights')
                await faceapi.loadFaceRecognitionModel('/weights')
            }

            videoEl.srcObject = await navigator.mediaDevices.getUserMedia({video: {}})
        }

        document.addEventListener('DOMContentLoaded', () => {
            console.log('start')
            run()
        })
    </script>
@endpush
