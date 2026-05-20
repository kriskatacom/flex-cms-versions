import axios from "axios";

export default (id, initialState) => ({
    id: id,
    isOpen: initialState,

    toggle() {
        this.isOpen = !this.isOpen;
        this.syncState();
    },

    syncState() {
        axios
            .post("/admin/ui/save-state", {
                section_id: this.id,
                state: this.isOpen,
            })
            .catch((err) => {
                console.error(
                    "Error saving section state:",
                    err.response ? err.response.data : err.message,
                );
            });
    },
});
