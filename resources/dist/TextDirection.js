const { Extension } = window.FilamentRichEditor.tiptap.core;

export default Extension.create({
    name: "customTextDirection",

    addOptions() {
        return {
            types: ["paragraph", "heading"],
        };
    },

    addGlobalAttributes() {
        return [
            {
                types: this.options.types,
                attributes: {
                    dir: {
                        default: null,
                        parseHTML: (element) => element.getAttribute("dir"),
                        renderHTML: (attributes) =>
                            attributes.dir ? { dir: attributes.dir } : {},
                    },
                },
            },
        ];
    },
});
