<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Referral Tree</title>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="container-fluid bg-white">
    <div class="col-12 mt-2 d-flex justify-content-between align-items-center">
        <div>
            <div>(Referral ID) / Left Side Total | Right Side Total| {{ $rootId }}</div>
            @if(isset($parentNodeId) && $parentNodeId)
                <a href="/referrals/tree-view/{{ $parentNodeId }}" class="btn btn-sm btn-outline-primary mt-1">
                    <i class="fas fa-level-up-alt"></i> Go to Parent
                </a>
            @elseif(isset($rootId) && $rootId)
                <a href="/referrals/tree-view" class="btn btn-sm btn-outline-primary mt-1">
                    <i class="fas fa-home"></i> Back to Top
                </a>
            @endif
        </div>

        <!-- Zoom Controls -->
        <div class="zoom-controls">
            <button id="zoom-in" class="btn btn-sm btn-primary" title="Zoom In">
                <i class="fas fa-search-plus"></i> Zoom In
            </button>
            <button id="zoom-reset" class="btn btn-sm btn-secondary" title="Reset Zoom">
                <i class="fas fa-compress"></i> 100%
            </button>
            <button id="zoom-out" class="btn btn-sm btn-primary" title="Zoom Out">
                <i class="fas fa-search-minus"></i> Zoom Out
            </button>
            <span class="ml-2 badge badge-info" id="zoom-level">100%</span>
        </div>
    </div>
    <div class="col overflow-auto" style="height: 100vh;">
        <div class="mt-2"></div>

        <!-- Start of tree structure -->
        <div class="genealogy-body genealogy-scroll" id="tree-container">
            <div class="genealogy-tree" id="tree-zoom">
                <ul>
                    @foreach ($referral_levels[0] as $root)
                        <livewire:referral.tree-content :node="$root" :level="1" />
                    @endforeach
                </ul>
            </div>

        </div>
        <!-- End of tree structure -->

    </div>
</div>
<style>
    /*----------------genealogy-scroll----------*/

    .genealogy-scroll::-webkit-scrollbar {
        width: 5px;
        height: 8px;
    }

    .genealogy-scroll::-webkit-scrollbar-track {
        border-radius: 10px;
        background-color: #e4e4e4;
    }

    .genealogy-scroll::-webkit-scrollbar-thumb {
        background: #212121;
        border-radius: 10px;
        transition: 0.5s;
    }

    .genealogy-scroll::-webkit-scrollbar-thumb:hover {
        background: #d5b14c;
        transition: 0.5s;
    }


    /*----------------genealogy-tree----------*/
    .genealogy-body {
        white-space: nowrap;
        overflow-y: hidden;
        padding: 50px;
        min-height: 500px;
        padding-top: 10px;
        text-align: center;
    }

    .genealogy-tree {
        display: inline-block;
    }

    .genealogy-tree ul {
        padding-top: 20px;
        position: relative;
        padding-left: 0px;
        display: flex;
        justify-content: center;
    }

    .genealogy-tree li {
        /* float: left; Removed to fix centering issues */
        text-align: center;
        list-style-type: none;
        position: relative;
        padding: 20px 5px 0 5px;
    }

    .genealogy-tree li::before,
    .genealogy-tree li::after {
        content: '';
        position: absolute;
        top: 0;
        right: 50%;
        border-top: 2px solid #ccc;
        width: 50%;
        height: 18px;
    }

    .genealogy-tree li::after {
        right: auto;
        left: 50%;
        border-left: 2px solid #ccc;
    }

    .genealogy-tree li:only-child::after,
    .genealogy-tree li:only-child::before {
        display: none;
    }

    .genealogy-tree li:only-child {
        padding-top: 0;
    }

    .genealogy-tree li:first-child::before,
    .genealogy-tree li:last-child::after {
        border: 0 none;
    }

    .genealogy-tree li:last-child::before {
        border-right: 2px solid #ccc;
        border-radius: 0 5px 0 0;
        -webkit-border-radius: 0 5px 0 0;
        -moz-border-radius: 0 5px 0 0;
    }

    .genealogy-tree li:first-child::after {
        border-radius: 5px 0 0 0;
        -webkit-border-radius: 5px 0 0 0;
        -moz-border-radius: 5px 0 0 0;
    }

    .genealogy-tree ul ul::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        border-left: 2px solid #ccc;
        width: 0;
        height: 20px;
    }

    .genealogy-tree li a {
        text-decoration: none;
        color: #666;
        font-family: arial, verdana, tahoma;
        font-size: 11px;
        display: inline-block;
        border-radius: 5px;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
    }

    .genealogy-tree li a:hover+ul li::after,
    .genealogy-tree li a:hover+ul li::before,
    .genealogy-tree li a:hover+ul::before,
    .genealogy-tree li a:hover+ul ul::before {
        border-color: #fbba00;
    }

    /*--------------memeber-card-design----------*/
    .member-view-box {
        padding: 0px 20px;
        text-align: center;
        border-radius: 4px;
        position: relative;
        width: 200px;
        margin: 0 auto; /* Center the box */
        display: inline-block; /* Make it inline-block for centering */
    }

    .member-image {
        width: 100%;
        position: relative;
        text-align: center;
    }

    .member-image img {
        width: 60px;
        height: 60px;
        border-radius: 6px;
        background-color: #000;
        z-index: 1;
    }

    /* Zoom Controls Styling */
    .zoom-controls {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .zoom-controls button {
        transition: all 0.3s ease;
    }

    .zoom-controls button:hover {
        transform: scale(1.1);
    }

    #tree-zoom {
        transition: transform 0.3s ease;
        transform-origin: top center;
    }

    #tree-container {
        overflow: auto !important;
    }

    /* Jump to tree icon styling */
    .member-details h3 a {
        opacity: 0.6;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .member-details h3 a:hover {
        opacity: 1;
        transform: scale(1.2);
        color: #007bff !important;
    }

</style>
<script>
    $(function() {
        // Livewire handles expand/collapse now - just show all loaded nodes
        $('.genealogy-tree ul').show();

        const $container = $('#tree-container');

        // Center the tree view on load
        setTimeout(function() {
            const $tree = $('#tree-zoom');
            const treeWidth = $tree.outerWidth();
            const containerWidth = $container.width();
            
            if (treeWidth > containerWidth) {
                $container.scrollLeft((treeWidth - containerWidth) / 2);
            }
        }, 100);

        // Zoom functionality
        let zoomLevel = 1.0;
        const minZoom = 0.3;
        const maxZoom = 3.0;
        const zoomStep = 0.1;
        const $treeZoom = $('#tree-zoom');
        const $zoomLevelDisplay = $('#zoom-level');

        function updateZoom() {
            $treeZoom.css('transform', `scale(${zoomLevel})`);
            $zoomLevelDisplay.text(Math.round(zoomLevel * 100) + '%');
        }

        $('#zoom-in').on('click', function() {
            if (zoomLevel < maxZoom) {
                zoomLevel += zoomStep;
                updateZoom();
            }
        });

        $('#zoom-out').on('click', function() {
            if (zoomLevel > minZoom) {
                zoomLevel -= zoomStep;
                updateZoom();
            }
        });

        $('#zoom-reset').on('click', function() {
            zoomLevel = 1.0;
            updateZoom();
        });

        // Mouse wheel zoom (Ctrl + Scroll)
        $('#tree-container').on('wheel', function(e) {
            if (e.ctrlKey || e.metaKey) {
                e.preventDefault();

                if (e.originalEvent.deltaY < 0) {
                    // Scroll up - zoom in
                    if (zoomLevel < maxZoom) {
                        zoomLevel += zoomStep;
                        updateZoom();
                    }
                } else {
                    // Scroll down - zoom out
                    if (zoomLevel > minZoom) {
                        zoomLevel -= zoomStep;
                        updateZoom();
                    }
                }
            }
        });

        // Keyboard shortcuts
        $(document).on('keydown', function(e) {
            // Ctrl/Cmd + Plus/Equals for zoom in
            if ((e.ctrlKey || e.metaKey) && (e.key === '+' || e.key === '=')) {
                e.preventDefault();
                if (zoomLevel < maxZoom) {
                    zoomLevel += zoomStep;
                    updateZoom();
                }
            }
            // Ctrl/Cmd + Minus for zoom out
            if ((e.ctrlKey || e.metaKey) && e.key === '-') {
                e.preventDefault();
                if (zoomLevel > minZoom) {
                    zoomLevel -= zoomStep;
                    updateZoom();
                }
            }
            // Ctrl/Cmd + 0 for reset
            if ((e.ctrlKey || e.metaKey) && e.key === '0') {
                e.preventDefault();
                zoomLevel = 1.0;
                updateZoom();
            }
        });
    });

    // Debug: Log Livewire requests
    document.addEventListener('livewire:load', function () {
        console.log('✅ Livewire loaded - lazy loading ready!');
    });

    document.addEventListener('livewire:update', function () {
        console.log('🔄 Livewire update - new data loaded from server!');
    });
</script>
@livewireScripts
</body>
</html>
