import { FileVersion } from "@/Models/FileVersion";
import { useContext } from "react";
import { SelectedDiffFilesContext } from "@/Pages/dashboard/customer/Files/Show";

const VersionCheckbox = ({ version }: { version: FileVersion }) => {
    const { selected, setSelected } = useContext(SelectedDiffFilesContext);
    return (
        <input
            type={"checkbox"}
            checked={selected.includes(version.id)}
            onChange={(e) => {
                if (e.target?.checked) {
                    if (selected.length >= 2) {
                        setSelected((prev) => {
                            const temp = prev;
                            temp.shift();
                            return [version.id, ...temp];
                        });
                    } else {
                        setSelected((prev) => [version?.id, ...prev]);
                    }
                } else {
                    setSelected((prev) =>
                        prev.filter((id) => id != version.id),
                    );
                }
            }}
        />
    );
};

export default VersionCheckbox;
