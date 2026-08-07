const { Node } = window.FilamentRichEditor.tiptap.core;

export default Node.create({
    name: "video",

    group: "block",

    selectable: true,

    draggable: true,

    atom: true,

    addOptions() {
        return {
            width: 16,
            height: 9,
        };
    },

    addAttributes() {
        return {
            src: { default: null },
            width: { default: 16, parseHTML: (el) => el.getAttribute("width") },
            height: { default: 9, parseHTML: (el) => el.getAttribute("height") },
            responsive: {
                default: true,
                parseHTML: (el) => el.classList.contains("responsive"),
            },
            controls: {
                default: true,
                parseHTML: (el) => el.hasAttribute("controls"),
            },
            autoplay: {
                default: false,
                parseHTML: (el) => el.hasAttribute("autoplay"),
            },
            muted: {
                default: false,
                parseHTML: (el) => el.hasAttribute("muted"),
            },
            loop: {
                default: false,
                parseHTML: (el) => el.hasAttribute("loop"),
            },
        };
    },

    parseHTML() {
        return [
            { tag: "div[data-video] video" },
            { tag: "video[src]" },
        ];
    },

    addCommands() {
        return {
            setVideo: (options) => ({ commands }) => {
                return commands.insertContent({
                    type: this.name,
                    attrs: options,
                });
            },
        };
    },

    renderHTML({ HTMLAttributes }) {
        return [
            "div",
            { "data-video": "", class: "video" },
            [
                "video",
                {
                    src: HTMLAttributes.src,
                    width: HTMLAttributes.width,
                    height: HTMLAttributes.height,
                    controls: HTMLAttributes.controls ? "true" : null,
                    autoplay: HTMLAttributes.autoplay ? "true" : null,
                    muted: HTMLAttributes.autoplay || HTMLAttributes.muted ? "true" : null,
                    loop: HTMLAttributes.loop ? "true" : null,
                    playsinline: HTMLAttributes.autoplay ? "true" : null,
                    preload: HTMLAttributes.autoplay ? "metadata" : null,
                    class: HTMLAttributes.responsive ? "responsive" : null,
                    style: HTMLAttributes.responsive
                        ? `aspect-ratio: ${HTMLAttributes.width} / ${HTMLAttributes.height}; width: 100%; height: auto;`
                        : null,
                },
            ],
        ];
    },
});
