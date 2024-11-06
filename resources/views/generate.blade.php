@extends('layouts.layout')
@section('title', 'ZeroDiffussion')
@section('content')

    <div class="container box" style="position: relative; z-index: 1000">
        <div id="checkpoint" class="fake-input">Select Checkpoint</div>
    </div>
    <input type="hidden" id="checkpoint-name">
    <input type="hidden" id="runtime-url" value="{{ $runtime }}">
    <div class="preloader">
        <div class="zr-loading"></div>
        <div class="zr-loading"></div>
        <div class="zr-loading"></div>
    </div>
    <div class="notification-area">
    </div>
    <div class="no-runtime">
        Please load checkpoint
    </div>
    <div class="modal">
        <div class="modal-box box">
            <div class="modal-header">
                <div class="modal-title">Select Checkpoint</div>
                <div class="modal-close">&times;</div>
            </div>
            <div class="modal-content">
                <input type="text" class="input" id="checkpoint-search" placeholder="Search or submit new checkpoint">
                <div class="checkpoint-list">
                    @foreach ($checkpoints as $checkpoint)
                        <?php
                            $cpname = explode('/', $checkpoint->checkpoint)[1];
                        ?>
                        <div class="checkpoint-group" data-checkpoint="{{ $checkpoint->checkpoint }}">
                            <div class="checkpoint-detail">
                                <div class="checkpoint-name">{{ $cpname }}</div>
                                <a class="checkpoint-path" href="https://huggingface.co/{{ $checkpoint->checkpoint }}" target="blank">{{ $checkpoint->checkpoint }}</a>
                            </div>
                            <div class="checkpoint-load btn">Load</div>
                        </div>
                    @endforeach
                    <div class="add-checkpoint-group" data-checkpoint="{{ $checkpoint->checkpoint }}">
                        <div class="checkpoint-detail">
                            <div class="checkpoint-name">{{ $cpname }}</div>
                            <a class="checkpoint-path" href="https://huggingface.co/{{ $checkpoint->checkpoint }}" target="blank">{{ $checkpoint->checkpoint }}</a>
                        </div>
                        <div class="checkpoint-add btn">Add New</div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <main class="container box" style="display: none">
        <div class="section">
            <div class="row">
                <div class="col-10">
                    <div class="input-group">
                        <label for="prompt">Prompt</label>
                        <textarea name="prompt" id="prompt" class="input" placeholder="An astronaut riding a sportbike in a moon">An astronaut riding a sportbike in a moon</textarea>
                    </div>
                    <div class="input-group">
                        <label for="negative-prompt">Negative Prompt</label>
                        <textarea name="negative-prompt" id="negative-prompt" class="input" placeholder="extra finger">bad-hands-5, FastNegativeV2</textarea>
                    </div>
                </div>
                <div class="col-2">
                    <div class="generate btn">Generate</div>
                </div>
            </div>
        </div>
        <div class="section">
            <p class="section-title">Config</p>
            <div class="row">
                <div class="col-6">
                    <div class="input-group">
                        <label for="sampling-step">Sampling Step</label>
                        <div class="value" id="value-sampling-step">25</div>
                        <input type="range" name="sampling-step" id="sampling-step" min="1" max="150" value="25" step="1">
                    </div>
                    <div class="input-group">
                        <label for="width">Image Width</label>
                        <div class="value" id="value-width">512</div>
                        <input type="range" name="width" id="width" min="512" max="2048" step="32" value="512">
                        <label for="height">Image Height</label>
                        <div class="value" id="value-height">512</div>
                        <input type="range" name="height" id="height" min="512" max="2048" step="32" value="512">
                    </div>
                    <div class="input-group">
                        <label for="cfg-scale">CFG Scale</label>
                        <div class="value" id="value-cfg-scale">7.5</div>
                        <input type="range" name="cfg-scale" id="cfg-scale" min="1.0" max="30.0" step="0.5" value="7.5">
                    </div>
                    <div class="input-group">
                        <label for="seed">Seed</label>
                        <input type="number" class="input numberonly" name="seed" id="seed" value="-1">
                    </div>
                </div>
                <div class="col-6">
                    <img class="generation-result" src="/assets/img/example.jpg">
                    <div class="generation-seed">
                        <label for="seed-result">Seed:</label>
                        <input type="text" class="input" id="seed-result" disabled value="12345678">
                    </div>
                    <div class="button-group">
                        <div class="save-image btn">Save Image</div>
                        <div class="reuse-seed btn">Re-use Seed</div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection