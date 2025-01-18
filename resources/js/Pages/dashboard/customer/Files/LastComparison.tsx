import PageCard from "@/Components/ui/PageCard";
import "./last-comparison.css";

const LastComparison = ({ result }: { result: string }) => {
    return (
        <PageCard>
            <div
                dangerouslySetInnerHTML={{
                    __html: result,
                }}
            ></div>
        </PageCard>
    );
};

export default LastComparison;
