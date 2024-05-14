from langchain.llms import OpenAI
from dotenv import load_dotenv
import fitz,os

load_dotenv()
API_KEY = os.getenv("OPENAI_API_KEY")
llm = OpenAI(openai_api_key=API_KEY)

from langchain.text_splitter import CharacterTextSplitter
text_splitter = CharacterTextSplitter.from_tiktoken_encoder(
    chunk_size=1000, chunk_overlap=120
)

from langchain.document_loaders import PyPDFLoader
def chunks(pdf_file_path):
    loader = PyPDFLoader(pdf_file_path)
    docs = loader.load_and_split()
    return docs

from langchain.chains.retrieval_qa import retrieval_qa
from langchain.text_splitter import CharacterTextSplitter
from langchain.chains import ReduceDocumentsChain, MapReduceDocumentsChain
from langchain import PromptTemplate
from langchain.chains import LLMChain
from langchain.chains.combine_documents.stuff import StuffDocumentsChain

map_template = """The following is a set of documents
{docs}
Based on this list of docs, summarised into meaningful
Helpful Answer:"""

map_prompt = PromptTemplate.from_template(map_template)
map_chain = LLMChain(llm=llm, prompt=map_prompt)

reduce_template = """The following is set of summaries:
{doc_summaries}
Elaborate esterification section"""

reduce_prompt = PromptTemplate.from_template(reduce_template)
reduce_chain = LLMChain(llm=llm, prompt=reduce_prompt)

combine_documents_chain = StuffDocumentsChain(
    llm_chain=reduce_chain, document_variable_name="doc_summaries"
)
reduce_documents_chain = ReduceDocumentsChain(
    combine_documents_chain=combine_documents_chain,
    collapse_documents_chain=combine_documents_chain,
    token_max=5000,
)

map_reduce_chain = retrieval_qa(
    llm_chain=map_chain,
    reduce_documents_chain=reduce_documents_chain,
    document_variable_name="docs",
    return_intermediate_steps=False,
)

def summarize_pdf(file_path):
    split_docs = text_splitter.split_documents(chunks(file_path))
    return map_reduce_chain.run(split_docs)

result_summary=summarize_pdf("uploads/9-23.pdf")
print(result_summary)